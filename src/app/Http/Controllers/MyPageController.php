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
        return view("mypage.edit_profile", compact("user"));
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
        $profile_arguments = $request->only('post_code', 'address', 'building');
        $user = Auth::user();
        $id = Auth::id();
        $profile_arguments['user_id'] = $id;
        $profile = $user->profile;
        $request->merge(['user_id' => $id]);
        $image = $request->file('image');

        if ($image !== null) {
            $image_path = $image->store('public/image/profile');
            $profile_arguments['image_path'] = $image_path;
        }

        if ($profile) {
            if ($profile->getImagePath()) Storage::delete($profile->getImagePath());
        }

        Profile::upsert($profile_arguments, ['user_id']);
        return redirect('/');
    }
}
