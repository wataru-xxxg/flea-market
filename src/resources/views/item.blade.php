@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('search')
@include('components.search')
@endsection

@section('navigation')
@include('components.navigation')
@endsection

@section('content')
<div class="item-container">
    <div class="image-container">
        <img src="{{ asset(Storage::url($item->getImagePath())) }}" alt="商品画像" class="item-image">
    </div>

    <div class="details-container">
        <h1 class="item-title">{{ $item->name }}</h1>
        <p class="brand-name">{{ $item->brand }}</p>

        <p class="price"><span class="yen-mark">￥</span>{{ number_format($item->price) }} <span class="including-tax">(税込)</span></p>

        <div class="actions">
            <div class="star-button">
                <a href="/item/favorite/{{ $item->id }}">
                    <div class="icon">★</div>
                </a>
                @if ($item->favorites === null)
                <div>0</div>
                @else
                <div>{{ $item->favorites()->count() }}</div>
                @endif
            </div>
            <div class="comment-button">
                <div class="icon">💬</div>
                <div>1</div>
            </div>
        </div>

        <button class="buy-button">購入手続きへ</button>

        <h2 class="section-title">商品説明</h2>

        <div class="item-description">
            {{ $item->description }}
        </div>

        <h2 class="section-title">商品の情報</h2>

        <div class="item-category">
            <h3 class="block-title">カテゴリー</h3>
            <div class="category-group">
                @foreach ($item->categories as $category)
                <span class="category-tag">{{ $category->name }}</span>
                @endforeach
            </div>
        </div>

        <div class="item-condition">
            <h3 class="block-title">商品の状態</h3>
            @switch ($item->condition)
            @case (1)
            <span class="condition-tag">良好</span>
            @break
            @case (2)
            <span class="condition-tag">目立った傷や汚れなし</span>
            @break
            @case (3)
            <span class="condition-tag">やや傷や汚れあり</span>
            @case (4)
            <span class="condition-tag">状態が悪い</span>
            @break
            @endswitch
        </div>

        <h2 class="section-title">コメント(1)</h2>

        <div class="comments-section">
            <div class="comment">
                <div class="comment-avatar"></div>
                <div>
                    <div>admin</div>
                    <div class="comment-text">こちらにコメントが入ります。</div>
                </div>
            </div>
        </div>

        <h2 class="section-title">商品へのコメント</h2>

        <div class="comment-form">
            <textarea placeholder="コメントを入力"></textarea>
            <button class="comment-submit">コメントを送信する</button>
        </div>
    </div>
</div>
@endsection