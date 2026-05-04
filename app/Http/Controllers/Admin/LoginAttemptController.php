<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class LoginAttemptController extends Controller
{
    public function index(Request $request): View
    {
        abort_if(!Schema::hasTable('admin_login_attempts'), 500, 'admin_login_attempts tablosu bulunamadı.');

        $query = DB::table('admin_login_attempts');

        if ($request->filled('identifier')) {
            $query->where('identifier', 'like', '%' . $request->input('identifier') . '%');
        }

        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->input('ip_address') . '%');
        }

        if ($request->filled('status')) {
            if ($request->input('status') === 'success') {
                $query->where('successful', 1);
            }

            if ($request->input('status') === 'failed') {
                $query->where('successful', 0);
            }
        }

        $attempts = $query
            ->orderByDesc('id')
            ->paginate(30)
            ->withQueryString();

        $summary = [
            'total' => DB::table('admin_login_attempts')->count(),
            'success' => DB::table('admin_login_attempts')->where('successful', 1)->count(),
            'failed' => DB::table('admin_login_attempts')->where('successful', 0)->count(),
            'last_24h_failed' => DB::table('admin_login_attempts')
                ->where('successful', 0)
                ->where('created_at', '>=', now()->subDay())
                ->count(),
        ];

        return view('admin.login-attempts.index', [
            'attempts' => $attempts,
            'summary' => $summary,
            'filters' => $request->only(['identifier', 'ip_address', 'status']),
        ]);
    }
}
