<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>取引ステータス更新通知</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 30px;
        }

        .content {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .item-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .status-badge {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>取引ステータス更新通知</h1>
    </div>

    <div class="content">
        <p>{{ $seller->name }} 様</p>

        <p>お疲れ様です。フリーマーケットアプリをご利用いただき、ありがとうございます。</p>

        <p>以下の商品の取引ステータスが更新されました：</p>

        <div class="item-info">
            <h3>商品情報</h3>
            <p><strong>商品名：</strong>{{ $item->name }}</p>
            <p><strong>ブランド：</strong>{{ $item->brand }}</p>
            <p><strong>価格：</strong>¥{{ number_format($item->price) }}</p>
            <p><strong>購入者：</strong>{{ $buyer->name }}</p>
            <p><strong>新しいステータス：</strong><span class="status-badge">{{ $deal->status }}</span></p>
        </div>

        @if($deal->status === 'processing')
        <p>取引が進行中になりました。商品の発送準備をお願いします。</p>
        @elseif($deal->status === 'completed')
        <p>取引が完了しました。お疲れ様でした。</p>
        @endif

        <p>詳細はマイページからご確認いただけます。</p>

        <a href="{{ url('/mypage?page=deal') }}" class="button">マイページを開く</a>

        <div class="footer">
            <p>このメールは自動送信されています。返信はできませんのでご了承ください。</p>
            <p>ご不明な点がございましたら、お気軽にお問い合わせください。</p>
        </div>
    </div>
</body>

</html>