@extends('admin.layouts.app')

@section('title', 'Yeni Ekip Üyesi')
@section('topbar_title', 'Yeni Ekip Üyesi')

@section('content')
<div class="page-title">
    <h1>Yeni Ekip Üyesi Ekle</h1>
    <p>Yeni ekip, uzman, avukat veya danışman profili oluşturabilirsin.</p>
</div>

@include('admin.team-members.partials.form', [
    'teamMember' => null,
    'action' => route('admin.team-members.store'),
    'method' => 'POST',
    'buttonText' => 'Ekip Üyesini Kaydet'
])
@endsection