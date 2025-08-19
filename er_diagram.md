# フリーマーケットアプリケーション ER 図

## データベース構造図

```mermaid
erDiagram
    users {
        bigint id PK
        varchar name
        varchar email
        varchar password
        timestamp email_verified_at
        timestamp created_at
        timestamp updated_at
    }

    profiles {
        bigint id PK
        int user_id FK
        varchar imagePath
        varchar postCode
        varchar address
        varchar building
        timestamp created_at
        timestamp updated_at
    }

    categories {
        bigint id PK
        varchar name
        timestamp created_at
        timestamp updated_at
    }

    items {
        bigint id PK
        int user_id FK
        varchar name
        varchar brand
        varchar description
        varchar imagePath
        tinyint condition
        int price
        boolean purchased
        timestamp created_at
        timestamp updated_at
    }

    category_item {
        bigint id PK
        bigint category_id FK
        bigint item_id FK
        timestamp created_at
        timestamp updated_at
    }

    favorites {
        bigint id PK
        bigint item_id FK
        bigint user_id FK
        timestamp created_at
        timestamp updated_at
    }

    comments {
        bigint id PK
        bigint item_id FK
        bigint user_id FK
        varchar comment
        timestamp created_at
        timestamp updated_at
    }

    purchases {
        bigint id PK
        bigint item_id FK
        bigint user_id FK
        varchar deliveryAddress
        varchar payment
        timestamp created_at
        timestamp updated_at
    }

    deals {
        bigint id PK
        bigint purchase_id FK
        enum status
        timestamp created_at
        timestamp updated_at
    }

    reviews {
        bigint id PK
        bigint deal_id FK
        bigint user_id FK
        int rating
        timestamp created_at
        timestamp updated_at
    }

    messages {
        bigint id PK
        bigint deal_id FK
        bigint to_user_id FK
        bigint from_user_id FK
        varchar message
        varchar imagePath
        boolean read
        timestamp created_at
        timestamp updated_at
    }

    %% リレーションシップ
    users ||--|| profiles : "1対1"
    users ||--o{ items : "1対多"
    users ||--o{ favorites : "1対多"
    users ||--o{ comments : "1対多"
    users ||--o{ purchases : "1対多"
    users ||--o{ reviews : "1対多"
    users ||--o{ messages : "1対多 (to_user)"
    users ||--o{ messages : "1対多 (from_user)"

    categories ||--o{ category_item : "1対多"
    items ||--o{ category_item : "1対多"
    items ||--o{ favorites : "1対多"
    items ||--o{ comments : "1対多"
    items ||--o{ purchases : "1対多"

    purchases ||--|| deals : "1対1"
    deals ||--o{ reviews : "1対多"
    deals ||--o{ messages : "1対多"
```

## テーブル説明

### 主要テーブル

1. **users** - ユーザー情報

   - 基本的なユーザー認証情報を管理

2. **profiles** - ユーザープロフィール

   - 住所やプロフィール画像などの詳細情報

3. **items** - 商品情報

   - 出品される商品の詳細情報

4. **categories** - カテゴリ
   - 商品のカテゴリ分類

### 中間テーブル

5. **category_item** - カテゴリと商品の多対多関係
6. **favorites** - お気に入り機能
7. **comments** - 商品へのコメント

### 取引関連テーブル

8. **purchases** - 購入情報
9. **deals** - 取引状態管理
10. **reviews** - レビュー・評価
11. **messages** - 取引者間のメッセージ

## 主要なビジネスフロー

1. **商品出品**: users → items
2. **商品購入**: users → purchases → deals
3. **取引完了**: deals → reviews
4. **コミュニケーション**: deals → messages
