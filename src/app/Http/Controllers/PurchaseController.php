<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function address(Request $request, $item_id)
    {
        $user = Auth::user();
        $payment = $request->payment;
        $postCode = $request->only('postCode')['postCode'];
        $address = $request->only('address')['address'];
        $building = $request->only('building')['building'];

        return view('address', compact('item_id', 'user', 'payment', 'postCode', 'address', 'building'));
    }

    public function updateAddress(AddressRequest $request)
    {
        $user = Auth::user();
        $item_id = $request->item_id;
        $item = Item::find($item_id);
        $postCode = $request->postCode;
        $address = $request->address;
        $building = $request->building;
        $payment = $request->payment;

        return view('purchase', compact('user', 'item', 'postCode', 'address', 'building', 'payment'));
    }
}
