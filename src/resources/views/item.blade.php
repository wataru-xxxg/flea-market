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
            <div class="favorite-button">
                @if (count($item->favorites) > 0)
                <a href="/item/favorite/{{ $item->id }}" class="favorite-link">
                    <div class="favorite-icon favorite-added">★</div>
                </a>
                <div class="favorite-count">{{ $item->favorites()->count() }}</div>
                @else
                <a href="/item/favorite/{{ $item->id }}" class="favorite-link">
                    <div class="favorite-icon">★</div>
                </a>
                <div class="favorite-count">0</div>
                @endif
            </div>
            <div class="comment-button">
                <div class="comment-icon">💬</div>
                @if ($item->comments === null)
                <div class="comment-count">0</div>
                @else
                <div class="comment-count">{{ $item->comments()->count() }}</div>
                @endif
            </div>
        </div>

        <a href="/purchase/{{ $item->id }}" class="buy-button">購入手続きへ</a>

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

        <h2 class="section-title">コメント(
            <span>
                @if ($item->comments === null)
                0
                @else
                {{ $item->comments()->count() }}
                @endif
            </span>
            )
        </h2>

        <div class="comments-section">
            @foreach ($item->comments as $comment)
            <div class="comment">
                <figure class="comment-profile"></figure>
                <div class="comment-profile">
                    @if ($comment->user->profile === null)
                    <img src="{{ asset('default.png') }}" alt="プロフィール画像" class="profile-image">
                    @elseif ($comment->user->profile->getImagePath() === null)
                    <img src="{{ asset('default.png') }}" alt="プロフィール画像" class="profile-image">
                    @else
                    <img src="{{ asset(Storage::url($comment->user->profile->getImagePath())) }}" alt="プロフィール画像"
                        class="profile-image">
                    @endif
                    <div class="profile-name">{{ $comment->user->name }}</div>
                </div>
                <p class="comment-text">{{ $comment->comment }}</p>
            </div>
            @endforeach
        </div>

        <h2 class="section-title">商品へのコメント</h2>

        @error('comment')
        <div class="form-error">
            {{ $message }}
        </div>
        @enderror

        <form action="/item/comment/{{ $item->id }}" method="post" class="comment-form">
            @csrf
            <textarea name="comment">{{ old('comment') }}</textarea>
            <button class="comment-submit">コメントを送信する</button>
        </form>
    </div>
</div>
@endsection