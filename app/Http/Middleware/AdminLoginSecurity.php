<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class AdminLoginSecurity
{
    private int $maxAttempts = 5;
    private int $decayMinutes = 15;

    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->isAdminLoginPost($request)) {
            return $next($request);
        }

        $identifier = $this->identifier($request);
        $ip = (string) $request->ip();

        if ($this->isLocked($identifier, $ip)) {
            return redirect()
                ->to(url('/admin/login'))
                ->withErrors([
                    'email' => 'Çok fazla hatalı giriş denemesi yapıldı. Lütfen birkaç dakika sonra tekrar deneyin.',
                ])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        $response = $next($request);

        $success = $this->loginSucceeded($request, $response);

        $this->storeAttempt($request, $identifier, $ip, $success);

        if ($success) {
            $this->updateLastLogin($identifier, $ip);
        }

        return $response;
    }

    private function isAdminLoginPost(Request $request): bool
    {
        return $request->isMethod('POST') && $request->is('admin/login');
    }

    private function identifier(Request $request): string
    {
        $value = $request->input('email')
            ?: $request->input('username')
            ?: $request->input('user')
            ?: 'unknown';

        $value = mb_strtolower(trim((string) $value));

        return mb_substr($value, 0, 150);
    }

    private function isLocked(string $identifier, string $ip): bool
    {
        try {
            if (!Schema::hasTable('admin_login_attempts')) {
                return false;
            }

            $since = now()->subMinutes($this->decayMinutes);

            $failedCount = DB::table('admin_login_attempts')
                ->where('identifier', $identifier)
                ->where('ip_address', $ip)
                ->where('successful', 0)
                ->where('created_at', '>=', $since)
                ->count();

            return $failedCount >= $this->maxAttempts;
        } catch (\Throwable $exception) {
            return false;
        }
    }

    private function loginSucceeded(Request $request, Response $response): bool
    {
        $sessionKeys = [
            'admin_user_id',
            'admin_id',
            'user_id',
            'auth_user_id',
        ];

        foreach ($sessionKeys as $key) {
            if (!empty(session($key))) {
                return true;
            }
        }

        try {
            if (function_exists('auth') && auth()->check()) {
                return true;
            }
        } catch (\Throwable $exception) {
            //
        }

        if ($response->isRedirection()) {
            $target = method_exists($response, 'getTargetUrl')
                ? (string) $response->getTargetUrl()
                : '';

            if ($target !== '' && !str_contains($target, '/admin/login')) {
                return true;
            }
        }

        return false;
    }

    private function storeAttempt(Request $request, string $identifier, string $ip, bool $success): void
    {
        try {
            if (!Schema::hasTable('admin_login_attempts')) {
                return;
            }

            DB::table('admin_login_attempts')->insert([
                'identifier' => $identifier,
                'ip_address' => mb_substr($ip, 0, 45),
                'user_agent' => mb_substr((string) $request->userAgent(), 0, 500),
                'successful' => $success ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $exception) {
            //
        }
    }

    private function updateLastLogin(string $identifier, string $ip): void
    {
        try {
            if (!Schema::hasTable('users')) {
                return;
            }

            if (!Schema::hasColumn('users', 'email')) {
                return;
            }

            $data = [];

            if (Schema::hasColumn('users', 'last_login_at')) {
                $data['last_login_at'] = now();
            }

            if (Schema::hasColumn('users', 'last_login_ip')) {
                $data['last_login_ip'] = mb_substr($ip, 0, 45);
            }

            if (Schema::hasColumn('users', 'updated_at')) {
                $data['updated_at'] = now();
            }

            if (!$data) {
                return;
            }

            DB::table('users')
                ->whereRaw('LOWER(email) = ?', [$identifier])
                ->update($data);
        } catch (\Throwable $exception) {
            //
        }
    }
}
