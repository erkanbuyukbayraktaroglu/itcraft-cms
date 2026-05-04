<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    private string $table = 'users';

    public function index(): View
    {
        $this->ensureUsersTable();

        $users = DB::table($this->table)
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.users.index', [
            'users' => $users,
            'columns' => $this->columns(),
            'currentUserId' => $this->currentUserId(),
        ]);
    }

    public function create(): View
    {
        $this->ensureUsersTable();

        return view('admin.users.create', [
            'columns' => $this->columns(),
            'user' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureUsersTable();

        $columns = $this->columns();

        $rules = [
            'email' => [
                'required',
                'email',
                Rule::unique($this->table, 'email'),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ];

        if ($columns['name']) {
            $rules['name'] = ['required', 'string', 'max:255'];
        }

        if ($columns['role']) {
            $rules['role'] = ['nullable', 'string', 'max:50'];
        }

        if ($columns['is_active']) {
            $rules['is_active'] = ['nullable', 'in:0,1'];
        }

        if ($columns['status']) {
            $rules['status'] = ['nullable', 'string', 'max:30'];
        }

        $validated = $request->validate($rules, [], [
            'name' => 'Ad Soyad',
            'email' => 'E-posta',
            'password' => 'Şifre',
        ]);

        $data = [
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ];

        if ($columns['name']) {
            $data['name'] = $validated['name'];
        }

        if ($columns['role']) {
            $data['role'] = $request->input('role') ?: 'admin';
        }

        if ($columns['is_active']) {
            $data['is_active'] = (int) $request->input('is_active', 1);
        }

        if ($columns['status']) {
            $data['status'] = $request->input('status') ?: 'active';
        }

        if ($columns['created_at']) {
            $data['created_at'] = now();
        }

        if ($columns['updated_at']) {
            $data['updated_at'] = now();
        }

        DB::table($this->table)->insert($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Admin kullanıcısı oluşturuldu.');
    }

    public function edit(int $id): View
    {
        $this->ensureUsersTable();

        $user = DB::table($this->table)->where('id', $id)->first();

        abort_if(!$user, 404);

        return view('admin.users.edit', [
            'user' => $user,
            'columns' => $this->columns(),
            'currentUserId' => $this->currentUserId(),
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $this->ensureUsersTable();

        $user = DB::table($this->table)->where('id', $id)->first();

        abort_if(!$user, 404);

        $columns = $this->columns();

        $rules = [
            'email' => [
                'required',
                'email',
                Rule::unique($this->table, 'email')->ignore($id),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ];

        if ($columns['name']) {
            $rules['name'] = ['required', 'string', 'max:255'];
        }

        if ($columns['role']) {
            $rules['role'] = ['nullable', 'string', 'max:50'];
        }

        if ($columns['is_active']) {
            $rules['is_active'] = ['nullable', 'in:0,1'];
        }

        if ($columns['status']) {
            $rules['status'] = ['nullable', 'string', 'max:30'];
        }

        $validated = $request->validate($rules, [], [
            'name' => 'Ad Soyad',
            'email' => 'E-posta',
            'password' => 'Şifre',
        ]);

        $data = [
            'email' => $validated['email'],
        ];

        if ($columns['name']) {
            $data['name'] = $validated['name'];
        }

        if ($columns['role']) {
            $data['role'] = $request->input('role') ?: 'admin';
        }

        if ($columns['is_active']) {
            $requestedActive = (int) $request->input('is_active', 0);

            if ($this->currentUserId() === $id && $requestedActive === 0) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Kendi kullanıcını pasife alamazsın.');
            }

            $data['is_active'] = $requestedActive;
        }

        if ($columns['status']) {
            $requestedStatus = $request->input('status') ?: 'active';

            if ($this->currentUserId() === $id && in_array($requestedStatus, ['passive', 'inactive', 'disabled'], true)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Kendi kullanıcını pasife alamazsın.');
            }

            $data['status'] = $requestedStatus;
        }

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        if ($columns['updated_at']) {
            $data['updated_at'] = now();
        }

        DB::table($this->table)->where('id', $id)->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Admin kullanıcısı güncellendi.');
    }

    public function toggle(int $id): RedirectResponse
    {
        $this->ensureUsersTable();

        $user = DB::table($this->table)->where('id', $id)->first();

        abort_if(!$user, 404);

        if ($this->currentUserId() === $id) {
            return redirect()
                ->back()
                ->with('error', 'Kendi kullanıcını pasife alamazsın.');
        }

        $columns = $this->columns();

        if ($columns['is_active']) {
            $newValue = (int) !((int) ($user->is_active ?? 0));

            DB::table($this->table)
                ->where('id', $id)
                ->update([
                    'is_active' => $newValue,
                    'updated_at' => $columns['updated_at'] ? now() : ($user->updated_at ?? null),
                ]);

            return redirect()
                ->back()
                ->with('success', 'Kullanıcı durumu güncellendi.');
        }

        if ($columns['status']) {
            $currentStatus = strtolower((string) ($user->status ?? 'active'));
            $newStatus = in_array($currentStatus, ['active', 'aktif'], true) ? 'passive' : 'active';

            DB::table($this->table)
                ->where('id', $id)
                ->update([
                    'status' => $newStatus,
                    'updated_at' => $columns['updated_at'] ? now() : ($user->updated_at ?? null),
                ]);

            return redirect()
                ->back()
                ->with('success', 'Kullanıcı durumu güncellendi.');
        }

        return redirect()
            ->back()
            ->with('error', 'Bu tabloda aktif/pasif alanı bulunamadı.');
    }

    private function columns(): array
    {
        $this->ensureUsersTable();

        return [
            'id' => Schema::hasColumn($this->table, 'id'),
            'name' => Schema::hasColumn($this->table, 'name'),
            'email' => Schema::hasColumn($this->table, 'email'),
            'password' => Schema::hasColumn($this->table, 'password'),
            'role' => Schema::hasColumn($this->table, 'role'),
            'is_active' => Schema::hasColumn($this->table, 'is_active'),
            'status' => Schema::hasColumn($this->table, 'status'),
            'last_login_at' => Schema::hasColumn($this->table, 'last_login_at'),
            'created_at' => Schema::hasColumn($this->table, 'created_at'),
            'updated_at' => Schema::hasColumn($this->table, 'updated_at'),
        ];
    }

    private function currentUserId(): ?int
    {
        $sessionKeys = [
            'admin_user_id',
            'admin_id',
            'user_id',
            'auth_user_id',
        ];

        foreach ($sessionKeys as $key) {
            $value = session($key);

            if (!empty($value)) {
                return (int) $value;
            }
        }

        if (auth()->check()) {
            return (int) auth()->id();
        }

        return null;
    }

    private function ensureUsersTable(): void
    {
        abort_if(!Schema::hasTable($this->table), 500, 'users tablosu bulunamadı.');
    }
}
