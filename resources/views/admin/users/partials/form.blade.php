@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="card">
    <form method="post" action="{{ $action }}">
        @csrf

        @if($method !== 'POST')
            @method($method)
        @endif

        <div class="form-grid form-grid-2">
            @if($columns['name'])
                <div>
                    <label for="name">Ad Soyad</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
                </div>
            @endif

            <div>
                <label for="email">E-posta</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
            </div>

            @if($columns['role'])
                <div>
                    <label for="role">Rol</label>
                    <select id="role" name="role" class="form-control">
                        @php
                            $selectedRole = old('role', $user->role ?? 'admin');
                        @endphp
                        <option value="admin" @selected($selectedRole === 'admin')>Admin</option>
                        <option value="editor" @selected($selectedRole === 'editor')>Editor</option>
                        <option value="viewer" @selected($selectedRole === 'viewer')>Viewer</option>
                    </select>
                </div>
            @endif

            @if($columns['is_active'])
                <div>
                    <label for="is_active">Durum</label>
                    @php
                        $selectedActive = (string) old('is_active', isset($user) ? (int) ($user->is_active ?? 1) : 1);
                    @endphp
                    <select id="is_active" name="is_active" class="form-control">
                        <option value="1" @selected($selectedActive === '1')>Aktif</option>
                        <option value="0" @selected($selectedActive === '0')>Pasif</option>
                    </select>
                </div>
            @endif

            @if($columns['status'])
                <div>
                    <label for="status">Durum</label>
                    @php
                        $selectedStatus = old('status', $user->status ?? 'active');
                    @endphp
                    <select id="status" name="status" class="form-control">
                        <option value="active" @selected($selectedStatus === 'active')>active</option>
                        <option value="passive" @selected($selectedStatus === 'passive')>passive</option>
                    </select>
                </div>
            @endif

            <div>
                <label for="password">Şifre</label>
                <input type="password" id="password" name="password" class="form-control" @if(!$user) required @endif>
                @if($user)
                    <small style="display:block;color:#6b7280;margin-top:6px;">Boş bırakırsan mevcut şifre değişmez.</small>
                @else
                    <small style="display:block;color:#6b7280;margin-top:6px;">En az 8 karakter önerilir.</small>
                @endif
            </div>

            <div>
                <label for="password_confirmation">Şifre Tekrar</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" @if(!$user) required @endif>
            </div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:22px;">
            <button type="submit" class="btn">Kaydet</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Vazgeç</a>
        </div>
    </form>
</div>