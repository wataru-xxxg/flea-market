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
    @if (isset($mylist))
    <a href="/">おすすめ</a>
    <a href="/?page=mylist" class="active">マイリスト</a>
    @else
    <a href="/" class="active">おすすめ</a>
    <a href="/?page=mylist">マイリスト</a>
    @endif
</nav>

<div class="items-grid">
    @if (isset($mylist))
    @foreach (Auth::user()->favorites as $favorite)
    <figure class="item-card">
        <a href="/item/{{ $favorite->item->id }}"><img src="{{ asset(Storage::url($favorite->item->getImagePath())) }}" alt="商品画像" class="item-image @if ($favorite->item->purchased === 1) grayed-out @endif"></a>
        <figcaption class="item-name">{{ $favorite->item->name }}</figcaption>
        @if ($favorite->item->purchased === 1)
        <p class="sold">Sold</p>
        @endif
    </figure>
    @endforeach
    @else
    @foreach ($items as $item)
    <figure class="item-card">
        <a href="/item/{{ $item->id }}"><img src="{{ asset(Storage::url($item->getImagePath())) }}" alt="商品画像" class="item-image @if ($item->purchased === 1) grayed-out @endif"></a>
        <figcaption class="item-name">{{ $item->name }}</figcaption>
        @if ($item->purchased === 1)
        <p class="sold">Sold</p>
        @endif
    </figure>
    @endforeach
    @endif
</div>
@endsection