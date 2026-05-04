<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('admin_user_id')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = DB::table('users')
            ->where('email', $validated['email'])
            ->where('is_active', 1)
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return back()
                ->withInput(['email' => $validated['email']])
                ->with('error', 'E-posta veya şifre hatalı.');
        }

        if (($user->role ?? null) !== 'admin') {
            return back()
                ->withInput(['email' => $validated['email']])
                ->with('error', 'Bu kullanıcı admin yetkisine sahip değil.');
        }

        $request->session()->regenerate();

        $request->session()->put([
            'admin_user_id' => $user->id,
            'admin_user_name' => $user->name,
            'admin_user_email' => $user->email,
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget([
            'admin_user_id',
            'admin_user_name',
            'admin_user_email',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Başarıyla çıkış yapıldı.');
    }
}
