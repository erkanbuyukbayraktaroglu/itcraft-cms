<div class="card">
    <div class="form-grid form-grid-2">
        <div>
            <label for="name">Ad Soyad *</label>
            <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required>
            @error('name') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div>
            <label for="email">E-posta *</label>
            <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
            @error('email') <small class="form-error">{{ $message }}</small> @enderror
        </div>
    </div>

    <div class="form-grid form-grid-2">
        <div>
            <label for="password">Şifre {{ isset($user) ? '' : '*' }}</label>
            <input class="form-control" type="password" id="password" name="password" {{ isset($user) ? '' : 'required' }} autocomplete="new-password">
            @error('password') <small class="form-error">{{ $message }}</small> @enderror

            @if(isset($user))
                <small style="display:block; margin-top:6px; color:#6b7280;">Şifreyi değiştirmek istemiyorsan boş bırak.</small>
            @else
                <small style="display:block; margin-top:6px; color:#6b7280;">Şifre en az 8 karakter olmalıdır.</small>
            @endif
        </div>

        <div>
            <label for="password_confirmation">Şifre Tekrarı {{ isset($user) ? '' : '*' }}</label>
            <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" {{ isset($user) ? '' : 'required' }} autocomplete="new-password">
        </div>
    </div>

    <div style="margin-top:16px;">
        <label>Durum</label>
        <div class="checkbox-row">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
                Aktif kullanıcı
            </label>
        </div>

        @if(isset($user) && (int) session('admin_user_id') === (int) $user->id)
            <small style="display:block; margin-top:6px; color:#6b7280;">
                Kendi kullanıcını pasif yapamazsın. Sistem otomatik olarak aktif bırakır.
            </small>
        @endif
    </div>
</div>