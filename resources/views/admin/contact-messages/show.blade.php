@extends('admin.layouts.app')

@section('title', 'Mesaj Detayı')
@section('topbar_title', 'Mesaj Detayı')

@section('content')
<div class="page-title">
    <h1>Mesaj Detayı</h1>
    <p>İletişim formundan gelen mesajın detaylarını görüntülüyorsun.</p>
</div>

<div class="card">
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom:22px;">
        <div>
            <label>Ad Soyad</label>
            <div style="padding:12px 14px; background:#f9fafb; border-radius:10px;">
                {{ $message->name ?? '-' }}
            </div>
        </div>

        <div>
            <label>E-posta</label>
            <div style="padding:12px 14px; background:#f9fafb; border-radius:10px;">
                @if(!empty($message->email))
                    <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                @else
                    -
                @endif
            </div>
        </div>

        <div>
            <label>Telefon</label>
            <div style="padding:12px 14px; background:#f9fafb; border-radius:10px;">
                {{ $message->phone ?? '-' }}
            </div>
        </div>

        <div>
            <label>Tarih</label>
            <div style="padding:12px 14px; background:#f9fafb; border-radius:10px;">
                {{ $message->created_at ?? '-' }}
            </div>
        </div>
    </div>

    <label>Konu</label>
    <div style="padding:12px 14px; background:#f9fafb; border-radius:10px; margin-bottom:18px;">
        {{ $message->subject ?? 'Konu belirtilmemiş' }}
    </div>

    <label>Mesaj</label>
    <div style="padding:18px; background:#f9fafb; border-radius:12px; line-height:1.7; white-space:pre-line; margin-bottom:22px;">
        {{ $message->message ?? '-' }}
    </div>

    <div style="border-top:1px solid #e5e7eb; padding-top:22px;">
        <h2 style="margin-top:0;">Gönderim Bilgileri</h2>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
            <div>
                <label>IP Adresi</label>
                <div style="padding:12px 14px; background:#f9fafb; border-radius:10px;">
                    {{ $message->ip_address ?? '-' }}
                </div>
            </div>

            <div>
                <label>Kaynak URL</label>
                <div style="padding:12px 14px; background:#f9fafb; border-radius:10px; word-break:break-word;">
                    {{ $message->source_url ?? '-' }}
                </div>
            </div>

            <div>
                <label>Referer</label>
                <div style="padding:12px 14px; background:#f9fafb; border-radius:10px; word-break:break-word;">
                    {{ $message->referer_url ?? '-' }}
                </div>
            </div>

            <div>
                <label>User-Agent</label>
                <div style="padding:12px 14px; background:#f9fafb; border-radius:10px; word-break:break-word;">
                    {{ $message->user_agent ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:22px;">
        <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-secondary">Geri Dön</a>

        @if(!empty($message->email))
            <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject ?? '' }}" class="btn">E-posta Yanıtla</a>
        @endif

        @if((int) ($message->is_read ?? 0) === 1)
            <form method="post" action="{{ route('admin.contact-messages.unread', $message->id) }}">
                @csrf
                <button type="submit" class="btn btn-secondary">Okunmadı Yap</button>
            </form>
        @else
            <form method="post" action="{{ route('admin.contact-messages.read', $message->id) }}">
                @csrf
                <button type="submit" class="btn btn-secondary">Okundu Yap</button>
            </form>
        @endif

        <form method="post" action="{{ route('admin.contact-messages.destroy', $message->id) }}" onsubmit="return confirm('Bu mesajı silmek istediğine emin misin?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Sil</button>
        </form>
    </div>
</div>
@endsection