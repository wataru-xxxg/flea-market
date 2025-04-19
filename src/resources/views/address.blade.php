@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('search')
@include('components.search')
@endsection

@section('navigation')
@include('components.navigation')
@endsection

@section('content')
<div class="container">
    <h1 class="title">住所の変更</h1>

    <form action="/purchase/address/{{ $item_id }}" method="post">
        @csrf
        <input type="hidden" name="name" value="{{ $user->name }}">

        <div class="form-group">
            <label for="post_code" class="form-label">郵便番号</label>
            <input type="text" name="postCode">

            @error('postCode')
            <div class="form-error">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="address" class="form-label">住所</label>
            <input type="text" name="address">

            @error('address')
            <div class="form-error">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="building" class="form-label">建物名</label>
            <input type="text" name="building">

            @error('building')
            <div class="form-error">
                {{ $message }}
            </div>
            @enderror
        </div>

        <button type="submit" class="submit-button">更新する</button>
    </form>
</div>
@endsection