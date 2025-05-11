<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use Tests\TestCase;
use Livewire\Livewire;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testImmediateReflection()
    {
        Item::factory()->create();

        $this->actingAs($this->user)
            ->get('/purchase/1')
            ->assertSeeLivewire('select')
            ->assertSeeLivewire('payment');

        Livewire::test('select')
            ->set('selectedPayment', 'card')
            ->assertEmitted('selectedPaymentUpdated');

        Livewire::test('payment')
            ->assertDontSee('カード支払い')
            ->emit('selectedPaymentUpdated', 'card')
            ->assertSee('カード支払い');
    }
}
