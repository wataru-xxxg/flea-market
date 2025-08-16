<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;



    protected $fillable = ['id', 'user_id', 'name', 'brand', 'description', 'imagePath', 'condition', 'price', 'purchased'];

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();;
    }

    public function getImagePath()
    {
        return $this->imagePath;
    }

    public function getId()
    {
        return $this->id;
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeLikeName($query, $keyword)
    {
        return $query->where('name', 'like', '%' . $keyword . '%');
    }

    public function scopeWhereInItemIds($query, $itemIds)
    {
        return $query->whereIn('id', $itemIds);
    }

    public function scopeNotMyItems($query, $userId)
    {
        return $query->where("user_id", "<>", $userId);;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
