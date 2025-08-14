<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page;

        $user = Auth::user();

        $items = Item::all();
        $deals = Deal::all();
        $unreadMessages = $user->unreadMessagesCount();

        if (is_null($page)) {
            return view("mypage.profile", compact("user", "items", "deals", "unreadMessages"));
        }

        if ($page == "buy") {
            $buy = true;
            return view("mypage.profile", compact("user", "buy", "unreadMessages"));
        }

        if ($page == "sell") {
            $sell = true;
            return view("mypage.profile", compact("user", "sell", "unreadMessages"));
        }

        if ($page == "deal") {
            $deal = true;
            return view("mypage.profile", compact("user", "deal", "unreadMessages"));
        }

        return view("mypage.profile", compact("user", "items", "unreadMessages"));
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
        User::find($request->id)->update(['name' => $request->name]);
        $profile_arguments = $request->only('postCode', 'address', 'building');
        $user = Auth::user();
        $id = Auth::id();
        $profile_arguments['user_id'] = $id;
        $profile = $user->profile;
        $request->merge(['user_id' => $id]);
        $image = $request->file('image');

        if ($image !== null) {
            $imagePath = $image->store('public/image/profile');
            $profile_arguments['imagePath'] = $imagePath;
        }

        if ($profile) {
            if ($profile->getImagePath()) Storage::delete($profile->getImagePath());
        }

        Profile::upsert($profile_arguments, ['user_id']);
        return redirect('/');
    }
}
