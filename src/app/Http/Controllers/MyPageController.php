<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Deal;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Message;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page;

        $user = Auth::user();

        $items = Item::all();
        $deals = Deal::with(['messages' => function ($query) use ($user) {
            $query->where('to_user_id', $user->id)
                ->where('read', false)
                ->orderBy('created_at', 'desc');
        }])->get()->sortByDesc(function ($deal) {
            return $deal->messages->first() ? $deal->messages->first()->created_at : $deal->created_at;
        });

        $unreadMessages = 0;
        foreach ($deals as $deal) {
            $unreadMessages += $deal->unreadMessagesCount($deal->id, $user->id);
        }

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
            return view("mypage.profile", compact("user", "deal", "deals", "unreadMessages"));
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

    public function chat($deal_id)
    {
        $user = Auth::user();
        $currentDeal = Deal::find($deal_id);
        $deals = Deal::with(['messages' => function ($query) use ($user) {
            $query->where('to_user_id', $user->id)
                ->where('read', false)
                ->orderBy('created_at', 'desc');
        }])->get()->sortByDesc(function ($deal) {
            return $deal->messages->first() ? $deal->messages->first()->created_at : $deal->created_at;
        });
        $messages = Message::where('deal_id', $deal_id)->get();
        $purchaserFlag = false;
        $item = Item::find($currentDeal->item->id);

        if ($currentDeal->purchasedUser->id === $user->id) {
            $purchaserFlag = true;
            $partner = $currentDeal->seller->item->user;
        } else {
            $partner = $currentDeal->purchasedUser;
        }

        foreach ($messages as $message) {
            if ($message->to_user_id === $user->id) {
                $message->read = true;
                $message->save();
            }
        }

        return view("mypage.chat", compact("currentDeal", "deals", "user", "messages", "purchaserFlag", "partner", "item"));
    }

    public function message(Request $request)
    {
        $fromUserId = Auth::user()->id;
        $deal = Deal::find($request->deal_id);
        $image = $request->file('image');

        if ($fromUserId === $deal->purchasedUser->id) {
            $toUserId = $deal->purchase->item->user->id;
        } else {
            $toUserId = $deal->purchasedUser->id;
        }

        $message = new Message();
        $message->deal_id = $request->deal_id;
        $message->from_user_id = $fromUserId;
        $message->to_user_id = $toUserId;
        $message->message = $request->message;
        if ($image !== null) {
            $imagePath = $image->store('public/image/message');
            $message->imagePath = $imagePath;
        }
        $message->save();

        return redirect()->back();
    }

    public function updateMessageAjax(Request $request, $message_id)
    {
        $message = Message::find($message_id);

        // メッセージの所有者かチェック
        if ($message->from_user_id !== Auth::user()->id) {
            return response()->json(['success' => false, 'message' => '権限がありません']);
        }

        $message->message = $request->message;
        $message->save();

        return response()->json(['success' => true, 'message' => $message->message]);
    }

    public function deleteMessageAjax($message_id)
    {
        $message = Message::find($message_id);

        // メッセージの所有者かチェック
        if ($message->from_user_id !== Auth::user()->id) {
            return response()->json(['success' => false, 'message' => '権限がありません']);
        }

        if ($message->imagePath) {
            Storage::delete($message->imagePath);
        }

        $message->delete();
        return response()->json(['success' => true]);
    }

    public function reviewAjax(Request $request, $deal_id)
    {
        $deal = Deal::find($deal_id);
        if ($deal->status === 'pending') {
            $deal->status = 'processing';
            $deal->save();
        } else {
            $deal->status = 'completed';
            $deal->save();
        }

        $review = new Review();
        $review->deal_id = $deal_id;
        $review->user_id = $request->partner_id;
        $review->rating = $request->rating;
        $review->save();

        return response()->json(['success' => true]);
    }
}
