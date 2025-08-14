<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Deal;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function payment(PurchaseRequest $request)
    {
        $item = Item::find($request->item_id);
        $item_id = $item->id;
        $postCode = $request->postCode;
        $address = $request->address;
        $building = $request->building;
        $deliveryAddress = $request->deliveryAddress;
        $payment = $request->payment;

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => [$payment],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('success', compact('item_id', 'deliveryAddress', 'payment')) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel', compact('item_id', 'postCode', 'address', 'building', 'payment')),
        ]);

        return redirect()->away($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        $item_id = $request->item_id;
        $changed_item_id = intval($item_id);
        $userId = Auth::id();

        $purchase = new Purchase();
        $purchase->item_id = $changed_item_id;
        $purchase->user_id = $userId;
        $purchase->deliveryAddress = $request->only('deliveryAddress')['deliveryAddress'];
        $purchase->payment = $request->only('payment')['payment'];
        $purchase->save();

        $item = Item::find($item_id)->update(['purchased' => 1]);

        $deal = new Deal();
        $deal->purchase_id = $purchase->id;
        $deal->save();

        return redirect('/');
    }

    public function cancel(Request $request)
    {
        $user = Auth::user();
        $item = Item::find($request->item_id);
        $postCode = $request->postCode;
        $address = $request->address;
        $building = $request->building;
        $payment = $request->payment;

        return view('purchase', compact('user', 'item', 'postCode', 'address', 'building', 'payment'));
    }
}
