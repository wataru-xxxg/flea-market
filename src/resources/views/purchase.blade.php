@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('livewire')
@livewireStyles
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
            <form action="/purchase/address/{{ $item->id }}" method="get">
                @csrf
                <div class="payment-section">
                    <h3 class="section-title">支払い方法</h3>
                    @if (isset($payment))
                    <livewire:select :payment="$payment" />
                    @else
                    <livewire:select />
                    @endif


                    @error('payment')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="divider"></div>

                <div class="delivery-section">
                    <div class="delivery-inner">
                        <h3 class="section-title">配送先</h3>
                        <input type="submit" class="change-button" value="変更する">
                    </div>

                    <div class="delivery-address">
                        @if (isset($postCode))
                        <input type="hidden" name="postCode" value="{{ $postCode }}">
                        <input type="hidden" name="address" value="{{ $address }}">
                        <input type="hidden" name="building" value="{{ $building }}">
                        <input type="hidden" name="deliveryAddress" value="{{ $postCode }}{{ $address }}{{ $building }}">
                        〒 {{ $postCode }}<br>
                        {{ $address }}<br>
                        {{ $building }}
                        @elseif (old('postCode'))
                        <input type="hidden" name="postCode" value="{{ old('postCode') }}">
                        <input type="hidden" name="address" value="{{ old('address') }}">
                        <input type="hidden" name="building" value="{{ old('building') }}">
                        <input type="hidden" name="deliveryAddress" value="{{ old('postCode') }}{{ old('address') }}{{ old('building') }}">
                        〒 {{ old('postCode') }}<br>
                        {{ old('address') }}<br>
                        {{ old('building') }}
                        @elseif ($user->profile)
                        <input type="hidden" name="postCode" value="{{ $user->profile->postCode }}">
                        <input type="hidden" name="address" value="{{ $user->profile->address }}">
                        <input type="hidden" name="building" value="{{ $user->profile->building }}">
                        <input type="hidden" name="deliveryAddress" value="{{ $user->profile->postCode }}{{ $user->profile->address }}{{ $user->profile->building }}">
                        〒 {{ $user->profile->postCode }}<br>
                        {{ $user->profile->address }}<br>
                        {{ $user->profile->building }}
                        @else
                        〒
                        <input type="hidden" name="deliveryAddress" value="">
                        @endif
                    </div>

                    @error('deliveryAddress')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </form>
        </div>

        <div class="checkout-right">
            <form action="/stripe/payment" method="post">
                @csrf

                <input type="hidden" name="item_id" value="{{ $item->id }}">

                @if (isset($postCode))
                <input type="hidden" name="postCode" value="{{ $postCode }}">
                <input type="hidden" name="address" value="{{ $address }}">
                <input type="hidden" name="building" value="{{ $building }}">
                <input type="hidden" name="deliveryAddress" value="{{ $postCode }}{{ $address }}{{ $building }}">
                @elseif ($user->profile)
                <input type="hidden" name="postCode" value="{{ $user->profile->post_code }}">
                <input type="hidden" name="address" value="{{ $user->profile->address }}">
                <input type="hidden" name="building" value="<input type=" hidden" name="postCode" value="{{ $user->profile->building }}">
                <input type="hidden" name="deliveryAddress" value="{{ $user->profile->post_code }}{{ $user->profile->address }}{{ $user->profile->building }}">
                @else
                <input type="hidden" name="deliveryAddress" value="">
                @endif

                <div class="order-summary">
                    <div class="summary-item">
                        <span class="item-label">商品代金</span>
                        <span class="item-value"><span class="yen-mark">￥</span>{{ number_format($item->price) }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="item-label">支払い方法</span>
                        @if (isset($payment))
                        <livewire:payment :payment="$payment" />
                        @else
                        <livewire:payment />
                        @endif

                    </div>
                </div>

                <input type="submit" class="purchase-button" value="購入する" formaction="/stripe/payment">
            </form>
        </div>
    </div>
</div>
@livewireScripts
@endsection