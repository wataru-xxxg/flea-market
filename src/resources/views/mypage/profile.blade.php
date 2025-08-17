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
<section class="profile-section">
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
            <div class="rating-display">
                @php
                $averageRating = $user->getAverageRating();
                @endphp
                <div class="stars">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <=$averageRating)
                        <span class="star filled">★</span>
                        @else
                        <span class="star empty">★</span>
                        @endif
                        @endfor
                </div>
            </div>
        </div>
        <div class="profile-actions">
            <a href="/mypage/profile" class="edit-profile-button">プロフィールを編集</a>
        </div>
    </div>

    <div class="items-container">
        <div class="item-tabs">
            @if (isset($buy))
            <a href="/mypage/?page=sell" class="tab">出品した商品</a>
            <a href="/mypage/?page=buy" class="tab active">購入した商品</a>
            <a href="/mypage/?page=deal" class="tab">取引中の商品
                @if ($unreadMessages > 0)
                <span class="notification-badge">{{ $unreadMessages }}</span>
                @endif
            </a>
            @elseif (isset($sell))
            <a href="/mypage/?page=sell" class="tab active">出品した商品</a>
            <a href="/mypage/?page=buy" class="tab">購入した商品</a>
            <a href="/mypage/?page=deal" class="tab">取引中の商品
                @if ($unreadMessages > 0)
                <span class="notification-badge">{{ $unreadMessages }}</span>
                @endif
            </a>
            @elseif (isset($deal))
            <a href="/mypage/?page=sell" class="tab">出品した商品</a>
            <a href="/mypage/?page=buy" class="tab">購入した商品</a>
            <a href="/mypage/?page=deal" class="tab active">取引中の商品
                @if ($unreadMessages > 0)
                <span class="notification-badge">{{ $unreadMessages }}</span>
                @endif
            </a>
            @else
            <a href="/mypage/?page=sell" class="tab">出品した商品</a>
            <a href="/mypage/?page=buy" class="tab">購入した商品</a>
            <a href="/mypage/?page=deal" class="tab">取引中の商品
                @if ($unreadMessages > 0)
                <span class="notification-badge">{{ $unreadMessages }}</span>
                @endif
            </a>
            @endif
        </div>

        <div class="items-grid">
            @if (isset($buy))
            @if ($user->purchases != null)
            @foreach ($user->purchases as $purchase)
            <figure class="item-card">
                <a href="/item/{{ $purchase->item->id }}"><img src="{{ asset(Storage::url($purchase->item->getImagePath())) }}" alt="商品画像" class="item-image grayed-out"></a>
                <figcaption class="item-name">{{ $purchase->item->name }}</figcaption>
                <p class="sold">Sold</p>
            </figure>
            @endforeach
            @endif
            @elseif (isset($sell))
            @if ($user->items != null)
            @foreach ($user->items as $item)
            <figure class="item-card">
                <a href="/item/{{ $item->id }}"><img src="{{ asset(Storage::url($item->getImagePath())) }}" alt="商品画像" class="item-image @if ($item->purchased === 1) grayed-out @endif"></a>
                <figcaption class="item-name">{{ $item->name }}</figcaption>
                @if ($item->purchased === 1)
                <p class="sold">Sold</p>
                @endif
            </figure>
            @endforeach
            @endif
            @elseif (isset($deal))
            @foreach ($deals as $deal)
            @if ($deal->status !== 'completed' && ($deal->purchasedUser->id === $user->id || $deal->seller->item->user->id === $user->id))
            <figure class="item-card">
                <a href="/mypage/chat/{{ $deal->item->id }}"><img src="{{ asset(Storage::url($deal->item->getImagePath())) }}" alt="商品画像" class="item-image"></a>
                <figcaption class="item-name">{{ $deal->item->name }}</figcaption>
                @if ($deal->unreadMessagesCount($deal->id, $user->id) > 0)
                <span class="notification-badge">{{ $deal->unreadMessagesCount($deal->id, $user->id) }}</span>
                @endif
            </figure>
            @endif
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
    </div>
</section>
@endsection