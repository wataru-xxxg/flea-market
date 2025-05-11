<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Item;
use Tests\TestCase;
use Mockery;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

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

    public function testCanPurchase()
    {
        $response = $this->actingAs($this->user)
            ->get('/purchase/' . $this->item->id);

        $response->assertStatus(200)
            ->assertViewIs('purchase')
            ->assertViewHas('item', $this->item);

        $response = $this->actingAs($this->user)
            ->post('/stripe/payment', [
                'item_id' => $this->item->id,
                'deliveryAddress' => 'test_address',
                'payment' => 'card'
            ]);

        $response->assertStatus(302)
            ->assertRedirect('https://checkout.stripe.com/test-session');


        $response = $this->actingAs($this->user)
            ->get(route('success', [
                'item_id' => $this->item->id,
                'deliveryAddress' => 'test_address',
                'payment' => 'card',
                'session_id' => 'test_session_id'
            ]));

        $response->assertStatus(302)
            ->assertRedirect('/');

        $this->assertDatabaseHas('purchases', [
            'item_id' => $this->item->id,
            'user_id' => $this->user->id,
            'deliveryAddress' => 'test_address',
            'payment' => 'card'
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $this->item->id,
            'purchased' => 1
        ]);
    }

    public function testSoldVisible()
    {
        $response = $this->actingAs($this->user)
            ->get('/purchase/' . $this->item->id);

        $response->assertStatus(200)
            ->assertViewIs('purchase')
            ->assertViewHas('item', $this->item);

        $response = $this->actingAs($this->user)
            ->post('/stripe/payment', [
                'item_id' => $this->item->id,
                'deliveryAddress' => 'test_address',
                'payment' => 'card'
            ]);

        $response->assertStatus(302)
            ->assertRedirect('https://checkout.stripe.com/test-session');


        $response = $this->actingAs($this->user)
            ->get(route('success', [
                'item_id' => $this->item->id,
                'deliveryAddress' => 'test_address',
                'payment' => 'card',
                'session_id' => 'test_session_id'
            ]));

        $response->assertStatus(302)
            ->assertRedirect('/');

        $response = $this->get('/');
        $response->assertSee('Sold');
    }

    public function testPurchasedItem()
    {
        $response = $this->actingAs($this->user)
            ->get('/purchase/' . $this->item->id);

        $response->assertStatus(200)
            ->assertViewIs('purchase')
            ->assertViewHas('item', $this->item);

        $response = $this->actingAs($this->user)
            ->post('/stripe/payment', [
                'item_id' => $this->item->id,
                'deliveryAddress' => 'test_address',
                'payment' => 'card'
            ]);

        $response->assertStatus(302)
            ->assertRedirect('https://checkout.stripe.com/test-session');


        $response = $this->actingAs($this->user)
            ->get(route('success', [
                'item_id' => $this->item->id,
                'deliveryAddress' => 'test_address',
                'payment' => 'card',
                'session_id' => 'test_session_id'
            ]));

        $response->assertStatus(302)
            ->assertRedirect('/');

        $response = $this->actingAs($this->user)
            ->get('/mypage/?page=buy');
        $response->assertSee('Sold');
    }
}
