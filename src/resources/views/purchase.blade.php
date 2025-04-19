@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('search')
@include('components.search')
@endsection

@section('navigation')
@include('components.navigation')
@endsection

@section('content')
<div class="container">
    <div class="checkout-grid">
        <div class="checkout-left">
            <div class="item-info">
                <img src="{{ asset(Storage::url($item->getImagePath())) }}" alt="商品画像" class="item-image">
                <div class="item-details">
                    <h2 class="item-name">{{ $item->name }}</h2>
                    <p class="item-price"><span class="yen-mark">￥</span>{{ number_format($item->price) }}</p>
                </div>
            </div>

            <div class="divider"></div>

            <div class="payment-section">
                <h3 class="section-title">支払い方法</h3>
                <select class="payment-dropdown">
                    <option>選択してください</option>
                    <option>コンビニ支払い</option>
                    <option>カード支払い</option>
                </select>
            </div>

            <div class="divider"></div>

            <div class="delivery-section">
                <div class="delivery-inner">
                    <h3 class="section-title">配送先</h3>
                    <a href="/purchase/address/{{ $item->id }}" class="change-button">変更する</a>
                </div>
                <div class="delivery-address">
                    @if (isset($deliveryAddress))
                    〒 {{ $deliveryAddress['postCode'] }}<br>
                    {{ $deliveryAddress['address'] }}<br>
                    {{ $deliveryAddress['building'] }}
                    @else
                    〒 {{ $user->profile->post_code }}<br>
                    {{ $user->profile->address }}<br>
                    {{ $user->profile->building }}
                    @endif
                </div>
            </div>
        </div>

        <div class="checkout-right">
            <div class="order-summary">
                <div class="summary-item">
                    <span class="item-label">商品代金</span>
                    <span class="item-value"><span class="yen-mark">￥</span>{{ number_format($item->price) }}</span>
                </div>
                <div class="summary-item">
                    <span class="item-label">支払い方法</span>
                    <span class="item-value">コンビニ払い</span>
                </div>
            </div>

            <button class="purchase-button">購入する</button>
        </div>
    </div>
</div>
@endsection