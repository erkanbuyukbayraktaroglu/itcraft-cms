@extends('admin.layouts.app')

@section('title', 'Admin Kullanıcıları')
@section('topbar_title', 'Admin Kullanıcıları')

@section('content')
<div class="page-title">
    <div>
        <h1>Admin Kullanıcıları</h1>
        <p>Panel kullanıcılarını görüntüleyebilir, yeni kullanıcı oluşturabilir ve mevcut kullanıcıları yönetebilirsin.</p>
    </div>

    <a href="{{ route('admin.users.create') }}" class="btn">Yeni Kullanıcı Ekle</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    @if($columns['name'])
                        <th>Ad Soyad</th>
                    @endif
                    <th>E-posta</th>
                    @if($columns['role'])
                        <th>Rol</th>
                    @endif
                    @if($columns['is_active'] || $columns['status'])
                        <th>Durum</th>
                    @endif
                    @if($columns['last_login_at'])
                        <th>Son Giriş</th>
                    @endif
                    @if($columns['created_at'])
                        <th>Oluşturma</th>
                    @endif
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>

                        @if($columns['name'])
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if($currentUserId === (int) $user->id)
                                    <span class="badge badge-info">Sen</span>
                                @endif
                            </td>
                        @endif

                        <td>{{ $user->email }}</td>

                        @if($columns['role'])
                            <td>{{ $user->role ?? '-' }}</td>
                        @endif

                        @if($columns['is_active'] || $columns['status'])
                            <td>
                                @if($columns['is_active'])
                                    @if((int) ($user->is_active ?? 0) === 1)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Pasif</span>
                                    @endif
                                @elseif($columns['status'])
                                    @php
                                        $status = strtolower((string) ($user->status ?? 'active'));
                                    @endphp

                                    @if(in_array($status, ['active', 'aktif']))
                                        <span class="badge badge-success">{{ $user->status }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ $user->status }}</span>
                                    @endif
                                @endif
                            </td>
                        @endif

                        @if($columns['last_login_at'])
                            <td>{{ $user->last_login_at ?? '-' }}</td>
                        @endif

                        @if($columns['created_at'])
                            <td>{{ $user->created_at ?? '-' }}</td>
                        @endif

                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-secondary btn-sm">Düzenle</a>

                                @if($columns['is_active'] || $columns['status'])
                                    <form method="post" action="{{ route('admin.users.toggle', $user->id) }}" onsubmit="return confirm('Kullanıcı durumunu değiştirmek istiyor musun?');">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary btn-sm" @if($currentUserId === (int) $user->id) disabled @endif>
                                            Aktif/Pasif
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Kullanıcı bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:18px;">
        {{ $users->links() }}
    </div>
</div>
@endsection