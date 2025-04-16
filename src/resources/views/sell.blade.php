@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('search')
@include('components.search')
@endsection

@section('navigation')
@include('components.navigation')
@endsection

@section('content')
<div class="container">


    <h1 class="main-title">商品の出品</h1>

    <form action="/sell" method="post" enctype="multipart/form-data">
        @csrf
        <section class="item-section">
            <label class="section-label">商品画像</label>
            <div class="image-upload-box">
                <input type="file" name="image" class="image-select-button">
            </div>
            @error('image')
            <div class="form-error">
                {{ $message }}
            </div>
            @enderror
        </section>

        <section class="item-section">
            <h2 class="section-title">商品の詳細</h2>
            <div class="item-details">
                <div class="form-group">
                    <label class="section-label">カテゴリー</label>
                    <div class="category-buttons">
                        @foreach ($categories as $category)
                        <input type="checkbox" name="categories[]" id="{{$category->id}}" class="category-checkbox" @if (is_array(old('categories')) && in_array($category->id, old('categories'))) checked @endif value="{{$category->id}}">
                        <label for="{{$category->id}}" class="category-button">{{$category->name}}</label>
                        @endforeach
                    </div>
                    @error('categories')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </section>

        <section class="item-section">
            <label class="section-label">商品の状態</label>
            <div class="item-condition">
                <select class="condition-select" name="condition">
                    <option selected disabled>選択してください</option>
                    <option value="1" {{ old('condition') == '1' ? 'selected' : ''}}>良好</option>
                    <option value="2" {{ old('condition') == '2' ? 'selected' : ''}}>目立った傷や汚れなし</option>
                    <option value="3" {{ old('condition') == '3' ? 'selected' : ''}}>やや傷や汚れあり</option>
                    <option value="4" {{ old('condition') == '4' ? 'selected' : ''}}>状態が悪い</option>
                </select>
            </div>
            @error('condition')
            <div class="form-error">
                {{ $message }}
            </div>
            @enderror
        </section>

        <section class="item-section">
            <h2 class="section-title">商品名と説明</h2>
            <div class="item-name-description">
                <div class="form-group">
                    <label class="section-label">商品名</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                    @error('name')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="section-label">ブランド名</label>
                    <input type="text" class="form-control" name="brand" value="{{ old('brand') }}">
                    @error('brand')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="section-label">商品の説明</label>
                    <textarea class="form-control" name="description">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="section-label">販売価格</label>
                    <div class="price-input">
                        <input type="text" class="form-control" name="price" value="{{ old('price') }}">
                    </div>
                    @error('price')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </section>

        <div class="submit-section">
            <button class="submit-button">出品する</button>
        </div>
    </form>
</div>
@endsection