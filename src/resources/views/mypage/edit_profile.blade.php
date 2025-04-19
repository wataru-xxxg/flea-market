@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/edit_profile.css') }}">
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

    <form action="/mypage/profile" method="post" enctype="multipart/form-data">
        @csrf
        <div class="profile-image-container">
            @if($user->profile === null)
            <img src="" alt="プロフィール画像" class="profile-image" id="profile-image">
            @elseif($user->profile->getImagePath() === null)
            <img src="" alt="プロフィール画像" class="profile-image" id="profile-image">
            @else
            <img src="{{ asset(Storage::url($user->profile->getImagePath())) }}" alt="プロフィール画像" class="profile-image" id="profile-image">
            @endif

            <input type="file" name="image" class="image-select-button">

            @error('image')
            <div class="image-error">
                {{ $message }}
            </div>
            @enderror
        </div>

        <input type="hidden" name="id" value="{{ $user->id }}">

        <div class="form-group">
            <label class="form-label">ユーザー名</label>

            @if (count($errors) > 0)
            <input type="text" class="form-input" name="name" value="{{ old('name') }}">
            @else
            <input type="text" class="form-input" name="name" value="{{ $user->name }}">
            @endif

            @error('name')
            <div class="form-error">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">郵便番号</label>

            @if (count($errors) > 0)
            <input type="text" class="form-input" name="post_code" value="{{ old('post_code') }}">
            @elseif($user->profile)
            <input type="text" class="form-input" name="post_code" value="{{ $user->profile->getPostCode() }}">
            @else
            <input type="text" class="form-input" name="post_code" value="">
            @endif

            @error('post_code')
            <div class="form-error">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">住所</label>

            @if (count($errors) > 0)
            <input type="text" class="form-input" name="address" value="{{ old('address') }}">
            @elseif ($user->profile)
            <input type="text" class="form-input" name="address" value="{{ $user->profile->getAddress() }}">
            @else
            <input type="text" class="form-input" name="address" value="">
            @endif

            @error('address')
            <div class="form-error">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">建物名</label>

            @if (count($errors) > 0)
            <input type="text" class="form-input" name="building" value="{{ old('building') }}">
            @elseif($user->profile)
            <input type="text" class="form-input" name="building" value="{{ $user->profile->getBuilding() }}">
            @else
            <input type="text" class="form-input" name="building" value="">
            @endif

            @error('building')
            <div class="form-error">
                {{ $message }}
            </div>
            @enderror
        </div>

        <button type="submit" class="update-button">更新する</button>
    </form>
</div>
@endsection