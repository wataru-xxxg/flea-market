<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Payment extends Component
{
    public $payment;
    public $selectedPayment = '';

    public function mount()
    {
        if ($this->payment == '') {
            $this->selectedPayment = old('payment', '');
        } else {
            $this->selectedPayment = $this->payment;
        }
    }

    protected $listeners = [
        'selectedPaymentUpdated' => 'handlePaymentUpdate'
    ];

    public function handlePaymentUpdate($selectedPayment)
    {
        $this->selectedPayment = $selectedPayment;
    }

    public function render()
    {
        return view('livewire.payment');
    }
}
