<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Tests\TestCase;

class DeliveryAddressTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDeliveryAddressChange()
    {
        $user = User::factory()->create();

        Item::factory()->create();

        $response = $this->actingAs($user)
            ->get('/purchase/1')
            ->assertDontSee('123-4567')
            ->assertDontSee('test_address')
            ->assertDontSee('test_building');

        $response = $response->assertStatus(200);

        $response = $this->actingAs($user)
            ->post('/purchase/address/1', [
                'postCode' => '123-4567',
                'address' => 'test_address',
                'building' => 'test_building'
            ])
            ->assertStatus(302);

        $response = $this->actingAs($user)
            ->get('/purchase/1');

        $response->assertSeeInOrder(['123-4567', 'test_address', 'test_building']);
    }

    public function testBindDeliveryAddress()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->get('/purchase/2')
            ->assertDontSee('123-4567')
            ->assertDontSee('test_address')
            ->assertDontSee('test_building');

        $response = $response->assertStatus(200);

        $response = $this->actingAs($user)
            ->post('/purchase/address/2', [
                'postCode' => '123-4567',
                'address' => 'test_address',
                'building' => 'test_building'
            ])
            ->assertStatus(302);

        $response = $this->actingAs($user)
            ->get('/purchase/1');

        $response->assertSeeInOrder(['123-4567', 'test_address', 'test_building']);

        $response = $this->actingAs($user)
            ->post('/stripe/payment', [
                'item_id' => $item->id,
                'deliveryAddress' => '123-4567' . 'test_address' . 'test_building',
                'payment' => 'card'
            ]);

        $response = $response->assertStatus(302);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
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
            'success_url' => route('success', ['item_id' => $item->id, 'deliveryAddress' => '123-4567' . 'test_address' . 'test_building', 'payment' => 'card']),
            'cancel_url' => route('cancel', ['item_id' => $item->id, 'postCode' => 'test_postCode', 'address' => 'test_address', 'building' => 'test_building', 'payment' => 'card']),
        ]);

        $response = $this->actingAs($user)
            ->get($session->success_url);

        $response->assertStatus(302);

        $this->assertDatabaseHas(
            'purchases',
            [
                'item_id' => $item->id,
                'user_id' => $user->id,
                'deliveryAddress' => '123-4567' . 'test_address' . 'test_building',
                'payment' => 'card'
            ]
        );
    }
}
