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
        $deals = $this->deals()->get();
        $unreadMessagesCount = 0;
        foreach ($deals as $deal) {
            $unreadMessagesCount += $deal->unreadMessagesCount($deal->id, $user->id);
        }

        return $unreadMessagesCount;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRating()
    {
        $reviews = $this->reviews;
        if ($reviews->count() === 0) {
            return 0;
        }

        $totalRating = $reviews->sum('rating');
        return round($totalRating / $reviews->count());
    }
}
