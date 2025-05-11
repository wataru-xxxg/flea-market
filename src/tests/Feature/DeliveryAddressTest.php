<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Tests\TestCase;
use Mockery;

class DeliveryAddressTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $item;

    protected $mockSession;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->item = Item::factory()->create([
            'purchased' => 0,
            'price' => 1000
        ]);

        $this->mockSession = Mockery::mock('overload:Stripe\Checkout\Session');

        $successUrl = route('success', [
            'item_id' => $this->item->id,
            'deliveryAddress' => 'test_address',
            'payment' => 'card'
        ]);

        $cancelUrl = route('cancel', [
            'item_id' => $this->item->id,
            'postCode' => '123-4567',
            'address' => 'test_address',
            'building' => 'test_building',
            'payment' => 'card'
        ]);

        $this->mockSession->shouldReceive('create')
            ->andReturn((object)[
                'url' => 'https://checkout.stripe.com/test-session',
                'id' => 'test_session_id',
                'success_url' => $successUrl . '?session_id=test_session_id'
            ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testDeliveryAddressChange()
    {
        $response = $this->actingAs($this->user)
            ->get('/purchase/' . $this->item->id)
            ->assertDontSee('123-4567')
            ->assertDontSee('test_address')
            ->assertDontSee('test_building');

        $response = $response->assertStatus(200);

        $response = $this->actingAs($this->user)
            ->post('/purchase/address/' . $this->item->id, [
                'postCode' => '123-4567',
                'address' => 'test_address',
                'building' => 'test_building'
            ])
            ->assertStatus(302);

        $response = $this->actingAs($this->user)
            ->get('/purchase/' . $this->item->id);

        $response->assertSeeInOrder(['123-4567', 'test_address', 'test_building']);
    }

    public function testBindDeliveryAddress()
    {
        $response = $this->actingAs($this->user)
            ->get('/purchase/' . $this->item->id)
            ->assertDontSee('123-4567')
            ->assertDontSee('test_address')
            ->assertDontSee('test_building');

        $response = $response->assertStatus(200);

        $response = $this->actingAs($this->user)
            ->post('/purchase/address/' . $this->item->id, [
                'postCode' => '123-4567',
                'address' => 'test_address',
                'building' => 'test_building'
            ])
            ->assertStatus(302);

        $response = $this->actingAs($this->user)
            ->get('/purchase/' . $this->item->id);

        $response->assertSeeInOrder(['123-4567', 'test_address', 'test_building']);

        $response = $this->actingAs($this->user)
            ->post('/stripe/payment', [
                'item_id' => $this->item->id,
                'deliveryAddress' => '123-4567' . 'test_address' . 'test_building',
                'payment' => 'card'
            ]);

        $response = $this->actingAs($this->user)
            ->post('/stripe/payment', [
                'item_id' => $this->item->id,
                'deliveryAddress' => '123-4567' . 'test_address' . 'test_building',
                'payment' => 'card'
            ]);

        $response->assertStatus(302)
            ->assertRedirect('https://checkout.stripe.com/test-session');


        $response = $this->actingAs($this->user)
            ->get(route('success', [
                'item_id' => $this->item->id,
                'deliveryAddress' => '123-4567' . 'test_address' . 'test_building',
                'payment' => 'card',
                'session_id' => 'test_session_id'
            ]));

        $response->assertStatus(302)
            ->assertRedirect('/');

        $this->assertDatabaseHas(
            'purchases',
            [
                'item_id' => $this->item->id,
                'user_id' => $this->user->id,
                'deliveryAddress' => '123-4567' . 'test_address' . 'test_building',
                'payment' => 'card'
            ]
        );
    }
}
