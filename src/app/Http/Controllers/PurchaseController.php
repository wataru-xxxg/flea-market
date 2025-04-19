<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function purchase($item_id)
    {
        $user = Auth::user();
        $item = Item::find($item_id);

        return view('purchase', compact('user', 'item'));
    }

    public function address($item_id)
    {
        $user = Auth::user();

        return view('address', compact('item_id', 'user'));
    }

    public function updateAddress(AddressRequest $request)
    {
        $user = Auth::user();
        $item_id = $request->item_id;
        $item = Item::find($item_id);
        $deliveryAddress = [
            'postCode' => $request->postCode,
            'address' => $request->address,
            'building' => $request->building
        ];

        return view('purchase', compact('user', 'item', 'deliveryAddress'));
    }
}
