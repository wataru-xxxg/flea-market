<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_id' => 'required',
            'payment' => 'required|not_in:disabled',
            'deliveryAddress' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'payment.required' => '支払い方法を選択して下さい',
            'payment.not_in' => '支払い方法を選択して下さい',
            'deliveryAddress.required' => '配送先を入力してください',
        ];
    }

    public function getRedirectUrl()
    {
        $item_id = $this->input('item_id');
        $postCode = $this->input('postCode');
        $address = $this->input('address');
        $building = $this->input('building');
        $payment = $this->input('payment');
        return route('purchase', compact('item_id', 'postCode', 'address', 'building', 'payment'));
    }
}
