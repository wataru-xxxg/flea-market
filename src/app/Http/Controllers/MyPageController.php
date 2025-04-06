<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyPageController extends Controller
{
    public function index()
    {
        return view("mypage.profile");
    }

    public function edit()
    {
        return view("mypage.editProfile");
    }
}
