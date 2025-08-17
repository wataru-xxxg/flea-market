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
<div class="container"
    data-is-seller="{{ $purchaserFlag ? 'false' : 'true' }}"
    data-deal-status="{{ $currentDeal->status }}">
    <!-- サイドバー -->
    <aside class="sidebar">
        <div class="sidebar-section">
            <h3 class="sidebar-title">その他の取引</h3>
        </div>
        <div class="product-list">
            @foreach ($deals as $deal)
            @if ($deal->status !== 'completed' && ($deal->purchasedUser->id === $user->id || $deal->seller->item->user->id === $user->id))
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
            @if ($user->id === $currentDeal->purchasedUser->id && $currentDeal->status === 'pending')
            <button type="button" class="trade-header-button" onclick="showFeedbackModal()">取引を完了する</button>
            @endif
        </div>

        <!-- 商品情報 -->
        <div class="product-info">
            <div class="product-image">
                <img src="{{ asset(Storage::url($item->getImagePath())) }}" alt="商品画像" class="image-placeholder">
            </div>
            <div class="product-details">
                <h3>{{ $item->name }}</h3>
                <p class="price">{{ $item->price }}円</p>
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
                <input type="hidden" name="deal_id" value="{{ $currentDeal->id }}">
                <div class="message-input-wrapper">
                    <input type="text" class="message-input" placeholder="取引メッセージを記入してください" name="message" id="message-input">
                </div>
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
            @if ($errors->any())
            <div class="error-message-container">
                @foreach ($errors->all() as $error)
                <div class="error-message">
                    {{ $error }}
                </div>
                @endforeach
            </div>
            @endif
        </form>
    </main>
</div>

<!-- フィードバックモーダル -->
<div id="feedbackModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">取引が完了しました。</h3>
        </div>
        <div class="modal-body">
            <p class="feedback-prompt">今回の取引相手はどうでしたか?</p>
            <div class="rating-container">
                <div class="stars">
                    <span class="star" data-rating="1">★</span>
                    <span class="star" data-rating="2">★</span>
                    <span class="star" data-rating="3">★</span>
                    <span class="star" data-rating="4">★</span>
                    <span class="star" data-rating="5">★</span>
                </div>
                <input type="hidden" id="selectedRating" value="0">
                <input type="hidden" id="partner_id" value="{{ $partner->id }}">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="submit-feedback-btn" onclick="submitFeedback('{{ $currentDeal->id }}')">送信する</button>
        </div>
    </div>
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

    // フィードバックモーダル関連の関数
    function showFeedbackModal() {
        document.getElementById('feedbackModal').style.display = 'flex';
        document.body.style.overflow = 'hidden'; // スクロールを無効化
    }

    function hideFeedbackModal() {
        document.getElementById('feedbackModal').style.display = 'none';
        document.body.style.overflow = 'auto'; // スクロールを有効化
        resetRating();
    }

    function resetRating() {
        const stars = document.querySelectorAll('.star');
        stars.forEach(star => {
            star.classList.remove('active');
        });
        document.getElementById('selectedRating').value = '0';
    }

    function submitFeedback(dealId) {
        const rating = document.getElementById('selectedRating').value;
        const partnerId = document.getElementById('partner_id').value;
        if (rating === '0') {
            alert('評価を選択してください。');
            return;
        }

        // CSRFトークンを取得
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // フィードバックをサーバーに送信
        fetch('/mypage/chat/review/' + dealId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                rating: rating,
                partner_id: partnerId
            })
        }).then(function(response) {
            return response.json();
        }).then(function(data) {
            if (data.success) {
                alert('フィードバックを送信しました。ありがとうございます！');
                hideFeedbackModal();
                // トップ画面にリダイレクト
                window.location.href = '/';
            } else {
                alert('フィードバックの送信に失敗しました。');
            }
        }).catch(function(error) {
            console.error('Error:', error);
            alert('フィードバックの送信に失敗しました。');
        });
    }

    // 星評価のクリックイベント
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                setRating(rating);
            });
        });

        // モーダル外クリックで閉じる
        document.getElementById('feedbackModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideFeedbackModal();
            }
        });

        // 出品者がprocessing状態の取引画面を開いた時に自動でモーダルを表示
        const container = document.querySelector('.container');
        const isSeller = container.getAttribute('data-is-seller') === 'true';
        const dealStatus = container.getAttribute('data-deal-status');
        if (isSeller && dealStatus === 'processing') {
            showFeedbackModal();
        }

        // メッセージ入力内容の保持・復元
        const messageInput = document.getElementById('message-input');
        const dealId = '{{ $currentDeal->id }}';
        const storageKey = `chat_message_${dealId}`;

        // 保存されたメッセージを復元
        const savedMessage = localStorage.getItem(storageKey);
        if (savedMessage) {
            messageInput.value = savedMessage;
        }

        // メッセージ入力時にlocalStorageに保存
        messageInput.addEventListener('input', function() {
            localStorage.setItem(storageKey, this.value);
        });

        // フォーム送信時にlocalStorageから削除
        const form = messageInput.closest('form');
        form.addEventListener('submit', function() {
            localStorage.removeItem(storageKey);
        });
    });

    function setRating(rating) {
        const stars = document.querySelectorAll('.star');
        const selectedRating = document.getElementById('selectedRating');

        selectedRating.value = rating;

        stars.forEach(star => {
            const starRating = star.getAttribute('data-rating');
            if (starRating <= rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }
</script>

@livewireScripts

@endsection