<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function unreadMessagesCount($deal_id, $user_id)
    {
        return $this->messages()->where('deal_id', $deal_id)->where('to_user_id', $user_id)->where('read', 0)->count();
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function item()
    {
        return $this->hasOneThrough(
            Item::class,
            Purchase::class,
            'id', // Purchaseテーブルの外部キー
            'id', // Itemテーブルの外部キー
            'purchase_id', // Dealテーブルの外部キー
            'item_id' // Purchaseテーブルの外部キー
        );
    }

    public function purchasedUser()
    {
        return $this->hasOneThrough(
            User::class,
            Purchase::class,
            'id', // Purchaseテーブルの外部キー
            'id', // Userテーブルの外部キー
            'purchase_id', // Dealテーブルの外部キー
            'user_id' // Purchaseテーブルの外部キー
        );
    }

    public function seller()
    {
        return $this->purchase()->with('item.user');
    }
}
