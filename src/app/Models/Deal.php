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

    public function unreadMessagesCount($deal_id)
    {
        return $this->messages()->where('deal_id', $deal_id)->where('read', 0)->count();
    }

    public function item()
    {
        return $this->hasOneThrough(Item::class, Purchase::class, 'id', 'id', 'purchase_id');
    }
}
