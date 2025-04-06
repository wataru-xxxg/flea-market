<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function mail(Request $request)
    {
        return view("mail");
    }
    public function firstTime(Request $request)
    {
        return view("first-time");
    }
    public function index(Request $request)
    {
        return view("index");
    }
}
