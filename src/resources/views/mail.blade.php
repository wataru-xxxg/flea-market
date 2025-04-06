@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mail.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="verification-message">
        <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
        <p>メール認証を完了してください。</p>
    </div>

    <a href="#" class="verification-button">認証はこちらから</a>

    <div class="resend-link">
        <a href="#">認証メールを再送する</a>
    </div>
</div>
@endsection