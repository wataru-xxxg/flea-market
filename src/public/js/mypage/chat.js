function editMessage(messageId) {
    // 編集フォームを表示
    document.getElementById("message-edit-form-" + messageId).style.display =
        "block";
    document.getElementById("message-actions-" + messageId).style.display =
        "none";
    document.getElementById("message-content-" + messageId).style.display =
        "none";

    // 入力フィールドにフォーカス
    document.getElementById("edit-input-" + messageId).focus();
}

function cancelEdit(messageId) {
    // 編集をキャンセルして元の表示に戻す
    document.getElementById("message-edit-form-" + messageId).style.display =
        "none";
    document.getElementById("message-actions-" + messageId).style.display =
        "block";
    document.getElementById("message-content-" + messageId).style.display =
        "block";
}

function updateMessage(event, messageId) {
    event.preventDefault();

    const newMessage = document.getElementById("edit-input-" + messageId).value;

    // CSRFトークンを取得
    const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    // メッセージを更新
    fetch("/mypage/chat/message/" + messageId + "/update", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token,
        },
        body: JSON.stringify({
            message: newMessage,
        }),
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.success) {
                // メッセージ内容を更新
                document.getElementById(
                    "message-content-" + messageId
                ).textContent = newMessage;
                // 編集フォームを非表示にして元の表示に戻す
                cancelEdit(messageId);
            } else {
                alert("メッセージの更新に失敗しました。");
            }
        })
        .catch(function (error) {
            console.error("Error:", error);
            alert("メッセージの更新に失敗しました。");
        });
}

function deleteMessage(messageId) {
    // 削除の確認
    if (!confirm("このメッセージを削除しますか？")) {
        return;
    }

    // CSRFトークンを取得
    const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    // メッセージを削除
    fetch("/mypage/chat/message/" + messageId + "/delete", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token,
        },
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.success) {
                // メッセージ要素を削除
                const messageElement = document.querySelector(
                    '[data-message-id="' + messageId + '"]'
                );
                if (messageElement) {
                    messageElement.remove();
                }
            } else {
                alert("メッセージの削除に失敗しました。");
            }
        })
        .catch(function (error) {
            console.error("Error:", error);
            alert("メッセージの削除に失敗しました。");
        });
}

// フィードバックモーダル関連の関数
function showFeedbackModal() {
    document.getElementById("feedbackModal").style.display = "flex";
    document.body.style.overflow = "hidden"; // スクロールを無効化
}

function hideFeedbackModal() {
    document.getElementById("feedbackModal").style.display = "none";
    document.body.style.overflow = "auto"; // スクロールを有効化
    resetRating();
}

function resetRating() {
    const stars = document.querySelectorAll(".star");
    stars.forEach((star) => {
        star.classList.remove("active");
    });
    document.getElementById("selectedRating").value = "0";
}

function submitFeedback(dealId) {
    const rating = document.getElementById("selectedRating").value;
    const partnerId = document.getElementById("partner_id").value;
    if (rating === "0") {
        alert("評価を選択してください。");
        return;
    }

    // CSRFトークンを取得
    const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    // フィードバックをサーバーに送信
    fetch("/mypage/chat/review/" + dealId, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token,
        },
        body: JSON.stringify({
            rating: rating,
            partner_id: partnerId,
        }),
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.success) {
                alert("フィードバックを送信しました。ありがとうございます！");
                hideFeedbackModal();
                // トップ画面にリダイレクト
                window.location.href = "/";
            } else {
                alert("フィードバックの送信に失敗しました。");
            }
        })
        .catch(function (error) {
            console.error("Error:", error);
            alert("フィードバックの送信に失敗しました。");
        });
}

// 星評価のクリックイベント
document.addEventListener("DOMContentLoaded", function () {
    const stars = document.querySelectorAll(".star");

    stars.forEach((star) => {
        star.addEventListener("click", function () {
            const rating = this.getAttribute("data-rating");
            setRating(rating);
        });
    });

    // モーダル外クリックで閉じる
    document
        .getElementById("feedbackModal")
        .addEventListener("click", function (e) {
            if (e.target === this) {
                hideFeedbackModal();
            }
        });

    // 出品者がprocessing状態の取引画面を開いた時に自動でモーダルを表示
    const container = document.querySelector(".container");
    const isSeller = container.getAttribute("data-is-seller") === "true";
    const dealStatus = container.getAttribute("data-deal-status");
    if (isSeller && dealStatus === "processing") {
        showFeedbackModal();
    }

    // メッセージ入力内容の保持・復元
    const messageInput = document.getElementById("message-input");
    const dealId = "{{ $currentDeal->id }}";
    const storageKey = `chat_message_${dealId}`;

    // 保存されたメッセージを復元
    const savedMessage = localStorage.getItem(storageKey);
    if (savedMessage) {
        messageInput.value = savedMessage;
    }

    // メッセージ入力時にlocalStorageに保存
    messageInput.addEventListener("input", function () {
        localStorage.setItem(storageKey, this.value);
    });

    // フォーム送信時にlocalStorageから削除
    const form = messageInput.closest("form");
    form.addEventListener("submit", function () {
        localStorage.removeItem(storageKey);
    });
});

function setRating(rating) {
    const stars = document.querySelectorAll(".star");
    const selectedRating = document.getElementById("selectedRating");

    selectedRating.value = rating;

    stars.forEach((star) => {
        const starRating = star.getAttribute("data-rating");
        if (starRating <= rating) {
            star.classList.add("active");
        } else {
            star.classList.remove("active");
        }
    });
}
