<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use App\Models\Category;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;


class ItemController extends Controller
{

    public function mail(Request $request)
    {
        return view("mail");
    }

    public function index(Request $request)
    {
        $items = Item::all();
        return view("index", compact("items"));
    }
    public function sell(Request $request)
    {
        $categories = Category::all();
        return view('sell', compact('categories'));
    }

    public function exhibit(ExhibitionRequest $request)
    {
        $item_arguments = $request->only('condition', 'name', 'brand', 'description', 'price');
        $item_arguments['purchased'] = 0;
        $id = Auth::id();
        $item_arguments['user_id'] = $id;
        $image = $request->file('image');
        $image_path = $image->store('public/image/item');
        $item_arguments['image_path'] = $image_path;

        Item::create($item_arguments)->categories()->sync($request->categories);
        return redirect('/');
    }

    public function item($item_id)
    {
        $item = Item::find($item_id);
        return view('item', compact('item'));
    }

    public function favorite($item_id)
    {
        $changed_item_id = intval($item_id);
        $user_id = Auth::id();
        $existingFavorite = Favorite::where('item_id', $changed_item_id)
            ->where('user_id', $user_id)
            ->first();

        if ($existingFavorite === null) {
            $favorite = new Favorite();
            $favorite->item_id = $changed_item_id;
            $favorite->user_id = $user_id;
            $favorite->save();
        }

        return redirect(route('item', ['item_id' => $item_id]));
    }

    public function comment(CommentRequest $request)
    {
        $item_id = $request->item_id;
        $changed_item_id = intval($item_id);
        $userId = Auth::id();

        $comment = new Comment();
        $comment->item_id = $changed_item_id;
        $comment->user_id = $userId;
        $comment->comment = $request->only('comment')['comment'];
        $comment->save();

        return redirect(route('item', ['item_id' => $item_id]));
    }
}
