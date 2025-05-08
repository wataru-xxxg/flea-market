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
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testImmediateReflection()
    {
        $user = User::factory()->create([
            'name' => 'test_user',
            'email' => 'mail@mail.com',
            'password' => bcrypt('testtest'),
            'email_verified_at' => date("Y-m-d H:i:s"),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'test_user',
            'email' => 'mail@mail.com',
        ]);

        $item = Item::factory()->create([
            'user_id' => 1,
            'name' => 'test',
            'brand' => 'test_brand',
            'description' => 'test_description',
            'image_path' => '/image/item/Test.jpg',
            'condition' => 1,
            'price' => 2000,
            'purchased' => 0
        ]);

        $this->assertDatabaseHas('items', [
            'user_id' => 1,
            'name' => 'test',
            'brand' => 'test_brand',
            'description' => 'test_description',
            'image_path' => '/image/item/Test.jpg',
            'condition' => 1,
            'price' => 2000,
            'purchased' => 0
        ]);

        $this->actingAs($user)
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
