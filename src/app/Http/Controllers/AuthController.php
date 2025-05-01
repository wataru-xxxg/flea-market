<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function mail()
    {
        return view("auth.mail");
    }
}
