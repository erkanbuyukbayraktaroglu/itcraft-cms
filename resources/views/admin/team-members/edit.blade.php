@extends('admin.layouts.app')

@section('title', 'Ekip Üyesi Düzenle')
@section('topbar_title', 'Ekip Üyesi Düzenle')

@section('content')
<div class="page-title">
    <h1>Ekip Üyesi Düzenle</h1>
    <p><strong>{{ $teamMember->name }}</strong> profilini düzenliyorsun.</p>
</div>

@include('admin.team-members.partials.form', [
    'teamMember' => $teamMember,
    'action' => route('admin.team-members.update', $teamMember->id),
    'method' => 'PUT',
    'buttonText' => 'Değişiklikleri Kaydet'
])
@endsection