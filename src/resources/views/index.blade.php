@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('search')
@include('components.search')
@endsection

@section('navigation')
@include('components.navigation')
@endsection

@section('content')
<nav class="tab-menu">
    <a href="#" class="active">おすすめ</a>
    <a href="#">マイリスト</a>
</nav>

<div class="product-container">
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