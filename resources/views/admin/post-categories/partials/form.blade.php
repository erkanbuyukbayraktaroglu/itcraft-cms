<form method="post" action="{{ $action }}">
    @csrf

    @if($method !== 'POST')
        @method($method)
    @endif

    @if($errors->any())
        <div class="alert-error">
            Lütfen form alanlarını kontrol edin.
        </div>
    @endif

    <div class="card" style="margin-bottom:22px;">
        <h2 style="margin-top:0;">Kategori Bilgileri</h2>

        <div class="form-grid form-grid-2">
            <div>
                <label for="title">Başlık</label>
                <input class="form-control" type="text" id="title" name="title"
                       value="{{ old('title', $category->title ?? '') }}" required>
                @error('title') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div>
                <label for="slug">Slug</label>
                <input class="form-control" type="text" id="slug" name="slug"
                       value="{{ old('slug', $category->
slug ?? '') }}"
                       placeholder="Boş bırakırsan başlıktan otomatik oluşur">
                @error('slug') <small class="form-error">{{ $message }}</small> @enderror
            </div>
        </div>

        <label for="description">Açıklama</label>
        <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description') <small class="form-error">{{ $message }}</small> @enderror

        <div class="form-grid form-grid-2">
            <div>
                <label for="sort_order">Sıralama</label>
                <input class="form-control" type="number" id="sort_order" name="sort_order"
                       value="{{ old('sort_order', $category->sort_order ?? 0) }}">
                @error('sort_order') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div>
                <label>&nbsp;</label>
                <div class="checkbox-row">
                    <label>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                        Aktif
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom:22px;">
        <h2 style="margin-top:0;">SEO Ayarları</h2>

        <label for="meta_title">Meta Başlık</label>
        <input class="form-control" type="text" id="meta_title" name="meta_title"
               value="{{ old('meta_title', $category->meta_title ?? '') }}">

        <label for="meta_description">Meta Açıklama</label>
        <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>

        <label for="meta_keywords">Meta Anahtar Kelimeler</label>
        <input class="form-control" type="text" id="meta_keywords" name="meta_keywords"
               value="{{ old('meta_keywords', $category->meta_keywords ?? '') }}"
               placeholder="kelime1, kelime2, kelime3">
    </div>

    <div style="display:flex; gap:12px; flex-wrap:wrap;">
        <button type="submit" class="btn">{{ $buttonText }}</button>
        <a href="{{ route('admin.post-categories.index') }}" class="btn btn-secondary">Geri Dön</a>
    </div>
</form>