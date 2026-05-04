<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $adminUserId = $request->session()->get('admin_user_id');

        if (!$adminUserId) {
            return redirect()->route('admin.login')->with('error', 'Lütfen admin paneline giriş yapın.');
        }

        $user = DB::table('users')
            ->where('id', $adminUserId)
            ->where('is_active', 1)
            ->first();

        if (!$user) {
            $request->session()->forget([
                'admin_user_id',
                'admin_user_name',
                'admin_user_email',
            ]);

            return redirect()->route('admin.login')->with('error', 'Oturum geçersiz. Lütfen tekrar giriş yapın.');
        }

        return $next($request);
    }
}
