# フリーマーケットアプリケーション ER 図（簡易版）

## データベース構造

```mermaid
graph TB
    subgraph "ユーザー管理"
        A[users<br/>ユーザー情報]
        B[profiles<br/>プロフィール]
    end

    subgraph "商品管理"
        C[categories<br/>カテゴリ]
        D[items<br/>商品]
        E[category_item<br/>カテゴリ商品]
    end

    subgraph "取引管理"
        F[purchases<br/>購入]
        G[deals<br/>取引]
        H[reviews<br/>レビュー]
    end

    subgraph "コミュニケーション"
        I[favorites<br/>お気に入り]
        J[comments<br/>コメント]
        K[messages<br/>メッセージ]
    end

    %% リレーションシップ
    A --> B
    A --> D
    A --> F
    A --> H
    A --> I
    A --> J
    A --> K

    C --> E
    D --> E
    D --> I
    D --> J
    D --> F

    F --> G
    G --> H
    G --> K

    style A fill:#e1f5fe
    style B fill:#e1f5fe
    style C fill:#f3e5f5
    style D fill:#f3e5f5
    style E fill:#f3e5f5
    style F fill:#e8f5e8
    style G fill:#e8f5e8
    style H fill:#e8f5e8
    style I fill:#fff3e0
    style J fill:#fff3e0
    style K fill:#fff3e0
```

## テーブル一覧

| テーブル名      | 説明                 | 主要なカラム                      |
| --------------- | -------------------- | --------------------------------- |
| `users`         | ユーザー基本情報     | id, name, email, password         |
| `profiles`      | ユーザープロフィール | user_id, address, imagePath       |
| `categories`    | 商品カテゴリ         | id, name                          |
| `items`         | 商品情報             | user_id, name, price, condition   |
| `category_item` | カテゴリ商品関連     | category_id, item_id              |
| `favorites`     | お気に入り           | user_id, item_id                  |
| `comments`      | 商品コメント         | user_id, item_id, comment         |
| `purchases`     | 購入情報             | user_id, item_id, deliveryAddress |
| `deals`         | 取引状態             | purchase_id, status               |
| `reviews`       | レビュー             | deal_id, user_id, rating          |
| `messages`      | メッセージ           | deal_id, from_user_id, to_user_id |

## 主要なリレーションシップ

- **1 対 1**: users ↔ profiles
- **1 対多**: users → items, purchases, reviews
- **多対多**: categories ↔ items (via category_item)
- **1 対 1**: purchases ↔ deals
- **1 対多**: deals → reviews, messages
