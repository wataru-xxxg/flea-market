<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Select extends Component
{
    public $payment = '';
    public $selectedPayment = '';

    public function mount()
    {
        if ($this->payment == '') {
            $this->selectedPayment = old('payment', '');
        } else {
            $this->selectedPayment = $this->payment;
        }
    }

    public function updatedSelectedPayment($value)
    {
        $this->selectedPayment = $value;
        $this->emit('selectedPaymentUpdated', $this->selectedPayment);
    }
    public function render()
    {
        return view('livewire.select');
    }
}
