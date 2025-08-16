@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/chat.css') }}">
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
    <!-- サイドバー -->
    <aside class="sidebar">
        <div class="sidebar-section">
            <h3 class="sidebar-title">その他の取引</h3>
        </div>
        <div class="product-list">
            @foreach ($deals as $deal)
            @if ($deal->finished === 0 && ($deal->purchasedUser->id === $user->id || $deal->seller->id === $user->id))
            <div class="product-item">
                <a href="/mypage/chat/{{ $deal->id }}" class="product-link">
                    <span>{{ $deal->item->name }}</span>
                </a>
            </div>
            @endif
            @endforeach
        </div>
    </aside>

    <!-- メインコンテンツ -->
    <main class="content">
        <!-- 取引相手情報 -->
        <div class="trade-header">
            <div class="user-icon">
                @if ($partner->profile && $partner->profile->getImagePath())
                <img src="{{ asset(Storage::url($partner->profile->getImagePath())) }}" alt="ユーザー画像" class="image-avatar">
                @else
                <img src="" alt="ユーザー画像" class="image-avatar">
                @endif
            </div>
            <h2 class="trade-header-title">「{{ $partner->name }}」さんとの取引画面</h2>
        </div>

        <!-- 商品情報 -->
        <div class="product-info">
            <div class="product-image">
                <img src="{{ asset(Storage::url($deal->item->getImagePath())) }}" alt="商品画像" class="image-placeholder">
            </div>
            <div class="product-details">
                <h3>{{ $deal->item->name }}</h3>
                <p class="price">{{ $deal->item->price }}円</p>
            </div>
        </div>

        <!-- チャット履歴 -->
        <div class="chat-history">
            @foreach ($messages as $message)
            <div class="message @if ($message->from_user_id === $user->id) own-message @else other-message @endif" data-message-id="{{ $message->id }}">
                <div class="message-info">
                    @if ($message->from_user_id === $user->id)
                    <div class="message-avatar">
                        @if ($user->profile && $user->profile->getImagePath())
                        <img src="{{ asset(Storage::url($user->profile->getImagePath())) }}" alt="ユーザー画像" class="image-avatar">
                        @else
                        <img src="" alt="ユーザー画像" class="image-avatar">
                        @endif
                    </div>
                    <div class="username">{{ $user->name }}</div>
                    @else
                    <div class="username">{{ $partner->name }}</div>
                    <div class="message-avatar">
                        @if ($partner->profile && $partner->profile->getImagePath())
                        <img src="{{ asset(Storage::url($partner->profile->getImagePath())) }}" alt="ユーザー画像" class="image-avatar">
                        @else
                        <img src="" alt="ユーザー画像" class="image-avatar">
                        @endif
                    </div>
                    @endif
                </div>
                <div class="message-content" id="message-content-{{ $message->id }}">{{ $message->message }}</div>
                @if ($message->imagePath)
                <div class="message-image">
                    <img src="{{ asset(Storage::url($message->imagePath)) }}" alt="メッセージ画像" class="message-image-img">
                </div>
                @endif
                @if ($message->from_user_id === $user->id)
                <div class="message-actions" id="message-actions-{{ $message->id }}">
                    <button type="button" class="edit-btn" onclick="editMessage('{{ $message->id }}')">編集</button>
                    <button type="button" class="delete-btn" onclick="deleteMessage('{{ $message->id }}')">削除</button>
                </div>
                <div class="message-edit-form" id="message-edit-form-{{ $message->id }}" style="display: none;">
                    <form onsubmit="updateMessage(event, '{{ $message->id }}')">
                        @csrf
                        <input type="text" class="edit-message-input" id="edit-input-{{ $message->id }}" value="{{ $message->message }}">
                        <div class="edit-actions">
                            <button type="submit" class="save-btn">保存</button>
                            <button type="button" class="cancel-btn" onclick="cancelEdit('{{ $message->id }}')">キャンセル</button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- メッセージ入力エリア -->
        <form action="/mypage/chat/message" method="post" enctype="multipart/form-data">
            @csrf
            <div class="message-input-area">
                <input type="hidden" name="deal_id" value="{{ $deal->id }}">
                <input type="text" class="message-input" placeholder="取引メッセージを記入してください" name="message">
                <div class="file-input-wrapper">
                    <input type="file" class="file-input" name="image" id="image-input">
                    <label for="image-input" class="image-btn">画像を追加</label>
                </div>
                <button class="send-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M22 2L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M22 2L15 22L11 13L2 9L22 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </form>
    </main>
</div>

<script>
    function editMessage(messageId) {
        // 編集フォームを表示
        document.getElementById('message-edit-form-' + messageId).style.display = 'block';
        document.getElementById('message-actions-' + messageId).style.display = 'none';
        document.getElementById('message-content-' + messageId).style.display = 'none';

        // 入力フィールドにフォーカス
        document.getElementById('edit-input-' + messageId).focus();
    }

    function cancelEdit(messageId) {
        // 編集をキャンセルして元の表示に戻す
        document.getElementById('message-edit-form-' + messageId).style.display = 'none';
        document.getElementById('message-actions-' + messageId).style.display = 'block';
        document.getElementById('message-content-' + messageId).style.display = 'block';
    }

    function updateMessage(event, messageId) {
        event.preventDefault();

        const newMessage = document.getElementById('edit-input-' + messageId).value;

        // CSRFトークンを取得
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // メッセージを更新
        fetch('/mypage/chat/message/' + messageId + '/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    message: newMessage
                })
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    // メッセージ内容を更新
                    document.getElementById('message-content-' + messageId).textContent = newMessage;
                    // 編集フォームを非表示にして元の表示に戻す
                    cancelEdit(messageId);
                } else {
                    alert('メッセージの更新に失敗しました。');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                alert('メッセージの更新に失敗しました。');
            });
    }

    function deleteMessage(messageId) {
        // 削除の確認
        if (!confirm('このメッセージを削除しますか？')) {
            return;
        }

        // CSRFトークンを取得
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // メッセージを削除
        fetch('/mypage/chat/message/' + messageId + '/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    // メッセージ要素を削除
                    const messageElement = document.querySelector('[data-message-id="' + messageId + '"]');
                    if (messageElement) {
                        messageElement.remove();
                    }
                } else {
                    alert('メッセージの削除に失敗しました。');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                alert('メッセージの削除に失敗しました。');
            });
    }
</script>

@livewireScripts

@endsection