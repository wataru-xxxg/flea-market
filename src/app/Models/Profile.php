<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = ['id',];

    protected $fillable = ['user_id', 'imagePath', 'postCode', 'address', 'building'];

    public function getImagePath()
    {
        return $this->image_path;
    }
    public function getPostCode()
    {
        return $this->post_code;
    }
    public function getAddress()
    {
        return $this->address;
    }
    public function getBuilding()
    {
        return $this->building;
    }

    public function isDefaultImage()
    {
        return $this->image_path === 'public/image/profile/default.png';
    }
}
