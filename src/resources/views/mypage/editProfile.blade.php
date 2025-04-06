@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/editProfile.css') }}">
@endsection

@section('search')
@include('components.search')
@endsection

@section('navigation')
@include('components.navigation')
@endsection

@section('content')
<div class="container">
    <h1 class="profile-title">プロフィール設定</h1>

    <form action="/mypage/profile" method="post">
        <div class="profile-image-container">
            <div class="profile-image"></div>
            <input type="file" name="image" class="image-select-button">
            <!-- <button class="image-select-button">画像を選択する</button> -->
        </div>

        <div class="form-group">
            <label class="form-label">ユーザー名</label>
            <input type="text" class="form-input">
        </div>

        <div class="form-group">
            <label class="form-label">郵便番号</label>
            <input type="text" class="form-input">
        </div>

        <div class="form-group">
            <label class="form-label">住所</label>
            <input type="text" class="form-input">
        </div>

        <div class="form-group">
            <label class="form-label">建物名</label>
            <input type="text" class="form-input">
        </div>

        <button type="submit" class="update-button">更新する</button>
    </form>
</div>
@endsection