<div>
    <input type="hidden" name="payment" value="{{ $selectedPayment }}">
    @switch ($selectedPayment)
    @case ('konbini')
    <span class="item-value">コンビニ払い</span>
    @break
    @case ('card')
    <span class="item-value">カード支払い</span>
    @break
    @default
    <span class="item-value"></span>
    @break
    @endswitch
</div>