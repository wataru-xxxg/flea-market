<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = ['id',];

    protected $fillable = ['user_id', 'name', 'brand', 'description', 'image_path', 'condition', 'price', 'purchased'];

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();;
    }

    public function getImagePath()
    {
        return $this->image_path;
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
