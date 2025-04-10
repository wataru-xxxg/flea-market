<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'imagePath', 'postCode', 'address', 'building'];

    public function getImagePath()
    {
        return $this->imagePath;
    }
    public function getPostCode()
    {
        return $this->postCode;
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
        return $this->imagePath === 'public/image/profile/default.png';
    }
}
