<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ContactController extends Controller
{
    private int $maxSubmissionsPerWindow = 3;
    private int $rateLimitWindowMinutes = 15;

    public function index(): View
    {
        return view('frontend.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Honeypot spam kontrolü
        |--------------------------------------------------------------------------
        */

        if ($request->filled('website')) {
            return redirect()
                ->route('contact.index')
                ->with('success', 'Mesajınız başarıyla alınmıştır.');
        }

        /*
        |--------------------------------------------------------------------------
        | Basit IP gönderim limiti
        |--------------------------------------------------------------------------
        */

        if ($this->tooManySubmissions($request)) {
            return redirect()
                ->route('contact.index')
                ->withInput($request->except(['website']))
                ->withErrors([
                    'message' => 'Kısa süre içinde çok fazla form gönderimi yapıldı. Lütfen birkaç dakika sonra tekrar deneyin.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validasyon
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'min:2', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'phone' => ['nullable', 'string', 'max:255'],
                'subject' => ['nullable', 'string', 'max:255'],
                'message' => ['required', 'string', 'min:5', 'max:5000'],
            ],
            [
                'name.required' => 'Ad soyad alanı zorunludur.',
                'name.min' => 'Ad soyad en az 2 karakter olmalıdır.',
                'email.required' => 'E-posta alanı zorunludur.',
                'email.email' => 'Geçerli bir e-posta adresi giriniz.',
                'message.required' => 'Mesaj alanı zorunludur.',
                'message.min' => 'Mesaj en az 5 karakter olmalıdır.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | DB kayıt
        |--------------------------------------------------------------------------
        */

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? 'Web Sitesi İletişim Formu',
            'message' => $validated['message'],
            'is_read' => 0,
            'read_at' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $securityPayload = $this->securityPayload($request);

        foreach ($securityPayload as $column => $value) {
            if ($this->contactMessagesHasColumn($column)) {
                $payload[$column] = $value;
            }
        }

        DB::table('contact_messages')->insert($payload);

        /*
        |--------------------------------------------------------------------------
        | SMTP mail gönderimi
        |--------------------------------------------------------------------------
        | Mail gönderimi başarısız olsa bile form gönderimi başarılı sayılır.
        |--------------------------------------------------------------------------
        */

        $this->sendContactNotification($validated, $request);

        return redirect()
            ->route('contact.index')
            ->with('success', 'Mesajınız başarıyla gönderildi. En kısa sürede sizinle iletişime geçeceğiz.');
    }

    private function tooManySubmissions(Request $request): bool
    {
        try {
            if (!Schema::hasTable('contact_messages')) {
                return false;
            }

            if (!Schema::hasColumn('contact_messages', 'ip_address')) {
                return false;
            }

            if (!Schema::hasColumn('contact_messages', 'created_at')) {
                return false;
            }

            $ipAddress = $request->ip();

            if (!$ipAddress) {
                return false;
            }

            $windowStart = date('Y-m-d H:i:s', strtotime('-' . $this->rateLimitWindowMinutes . ' minutes'));

            $count = DB::table('contact_messages')
                ->where('ip_address', $ipAddress)
                ->where('created_at', '>=', $windowStart)
                ->count();

            return $count >= $this->maxSubmissionsPerWindow;
        } catch (\Throwable $e) {
            Log::warning('Contact form rate limit check failed: ' . $e->getMessage());
            return false;
        }
    }

    private function securityPayload(Request $request): array
    {
        return [
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 2000),
            'referer_url' => mb_substr((string) $request->headers->get('referer', ''), 0, 2000),
            'source_url' => mb_substr((string) $request->fullUrl(), 0, 2000),
        ];
    }

    private function contactMessagesHasColumn(string $column): bool
    {
        try {
            return Schema::hasTable('contact_messages') && Schema::hasColumn('contact_messages', $column);
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function sendContactNotification(array $data, Request $request): void
    {
        try {
            $mailEnabled = (string) setting('mail_enabled', '0');

            if ($mailEnabled !== '1') {
                return;
            }

            $host = trim((string) setting('mail_host', ''));
            $port = (int) setting('mail_port', 587);
            $username = trim((string) setting('mail_username', ''));
            $password = (string) setting('mail_password', '');
            $encryption = trim((string) setting('mail_encryption', 'tls'));
            $fromAddress = trim((string) setting('mail_from_address', ''));
            $fromName = trim((string) setting('mail_from_name', setting('site_name', 'Web Sitesi')));
            $recipient = trim((string) setting('contact_recipient_email', ''));

            if ($host === '' || $username === '' || $password === '' || $fromAddress === '' || $recipient === '') {
                Log::warning('Contact mail notification skipped because SMTP settings are incomplete.');
                return;
            }

            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.transport', 'smtp');
            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', $port);
            Config::set('mail.mailers.smtp.username', $username);
            Config::set('mail.mailers.smtp.password', $password);
            Config::set('mail.mailers.smtp.encryption', $encryption !== '' ? $encryption : null);
            Config::set('mail.from.address', $fromAddress);
            Config::set('mail.from.name', $fromName);

            $subject = trim((string) ($data['subject'] ?? ''));

            if ($subject === '') {
                $subject = 'Yeni İletişim Formu Mesajı';
            }

            $mailBody = "Yeni bir iletişim formu mesajı alındı.\n\n";
            $mailBody .= "Ad Soyad: " . ($data['name'] ?? '-') . "\n";
            $mailBody .= "E-posta: " . ($data['email'] ?? '-') . "\n";
            $mailBody .= "Telefon: " . (($data['phone'] ?? '') !== '' ? $data['phone'] : '-') . "\n";
            $mailBody .= "Konu: " . $subject . "\n";
            $mailBody .= "IP Adresi: " . ($request->ip() ?? '-') . "\n";
            $mailBody .= "Referer: " . ($request->headers->get('referer') ?? '-') . "\n";
            $mailBody .= "User-Agent: " . ($request->userAgent() ?? '-') . "\n";
            $mailBody .= "Tarih: " . date('Y-m-d H:i:s') . "\n\n";
            $mailBody .= "Mesaj:\n";
            $mailBody .= $data['message'] ?? '-';

            Mail::raw($mailBody, function ($message) use ($recipient, $subject, $data, $fromAddress, $fromName) {
                $message->to($recipient)
                    ->from($fromAddress, $fromName)
                    ->replyTo($data['email'], $data['name'])
                    ->subject('İletişim Formu: ' . $subject);
            });
        } catch (\Throwable $e) {
            Log::error('Contact mail notification failed: ' . $e->getMessage());
        }
    }
}
