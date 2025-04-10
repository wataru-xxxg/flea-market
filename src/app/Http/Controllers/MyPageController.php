<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MyPageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view("mypage.profile", compact("user"));
    }

    public function edit()
    {
        $user = Auth::user();
        return view("mypage.editProfile", compact("user"));
    }

    public function upsert(AddressRequest $request)
    {
        if ($request->hasFile('image')) {
            Validator::make(
                $request->only('image'),
                (new ProfileRequest())->rules(),
                (new ProfileRequest())->messages()
            )->validate();
        }

        $user = Auth::user();
        $id = Auth::id();
        $profile = $user->profile;
        $request->merge(['user_id' => $id]);
        $image = $request->file('image');

        if ($image !== null) {
            $imagePath = $image->store('public/image/profile');
            $request->merge(['imagePath' => $imagePath]);
        } else {
            $request->merge(['imagePath' => 'public/image/profile/default.png']);
        }

        if ($profile && !($profile->isDefaultImage())) {
            Storage::delete($profile->getImagePath());
        }

        Profile::upsert($request->only('user_id', 'imagePath', 'postCode', 'address', 'building'), ['user_id'], ['imagePath', 'postCode', 'address', 'building']);
        return redirect('/');
    }
}
