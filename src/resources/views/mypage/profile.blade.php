@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/profile.css') }}">
@endsection

@section('search')
@include('components.search')
@endsection

@section('navigation')
@include('components.navigation')
@endsection

@section('content')
<div class="profile-section">
    <div class="profile-container">
        <div class="profile-image-container">
            @if($user->profile)
            <img src="{{ asset(Storage::url($user->profile->getImagePath())) }}" alt="プロフィール画像" class="profile-image">
            @else
            <img src="" alt="プロフィール画像" class="profile-image">
            @endif
        </div>
        <div class="profile-info">
            <h1 class="username">{{ $user->name }}</h1>
        </div>
        <div class="profile-actions">
            <a href="/mypage/profile" class="edit-profile-button">プロフィールを編集</a>
        </div>
    </div>
</div>

<div class="product-tabs">
    <div class="tab active">出品した商品</div>
    <div class="tab">購入した商品</div>
</div>

<div class="products-grid">
    <div class="product-card">
        <div class="product-image">商品画像</div>
        <div class="product-name">商品名</div>
    </div>
    <div class="product-card">
        <div class="product-image">商品画像</div>
        <div class="product-name">商品名</div>
    </div>
    <div class="product-card">
        <div class="product-image">商品画像</div>
        <div class="product-name">商品名</div>
    </div>
    <div class="product-card">
        <div class="product-image">商品画像</div>
        <div class="product-name">商品名</div>
    </div>
    <div class="product-card">
        <div class="product-image">商品画像</div>
        <div class="product-name">商品名</div>
    </div>
    <div class="product-card">
        <div class="product-image">商品画像</div>
        <div class="product-name">商品名</div>
    </div>
    <div class="product-card">
        <div class="product-image">商品画像</div>
        <div class="product-name">商品名</div>
    </div>
</div>
@endsection