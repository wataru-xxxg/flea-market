<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);;
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function setAttribute($key, $value)
    {
        if ($key !== $this->getRememberTokenName()) {
            parent::setAttribute($key, $value);
        }
    }

    public function deals()
    {
        return $this->hasManyThrough(Deal::class, Purchase::class, 'user_id', 'purchase_id');
    }

    public function unreadMessagesCount()
    {
        $user = Auth::user();
        $deals = $this->deals;
        $receivedUnreadMessagesCount = 0;
        $sentUnreadMessagesCount = 0;

        foreach ($deals as $deal) {
            $receivedUnreadMessagesCount += $deal->messages()->where('read', 0)->where('to_user_id', $user->id)->count();
            $sentUnreadMessagesCount += $deal->messages()->where('read', 0)->where('from_user_id', $user->id)->count();
        }

        return $receivedUnreadMessagesCount + $sentUnreadMessagesCount;
    }
}
