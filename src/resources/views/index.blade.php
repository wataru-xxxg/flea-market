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

<div class="items-grid">
    @foreach ($items as $item)
    <figure class="item-card">
        <a href="/item/{{ $item->id }}"><img src="{{ asset(Storage::url($item->getImagePath())) }}" alt="商品画像" class="item-image"></a>
        <figcaption class="item-name">{{ $item->name }}</figcaption>
    </figure>
    @endforeach
</div>
@endsection