<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $item;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->item = Item::factory()->create();
    }

    public function testFavoriteButton()
    {
        $response = $this->actingAs($this->user)
            ->get('/item/favorite/1');

        $this->assertDatabaseHas('favorites', [
            'item_id' => $this->user->id,
            'user_id' => $this->item->id,
        ]);

        $response = $response->assertStatus(302);

        $response = $this->get('/item/' . $this->item->id);

        $response->assertSeeInOrder(['div', 'class', 'favorite-count', '1', '/div']);
    }

    public function testIconColor()
    {
        $response = $this->get('/item/' . $this->item->id);

        $response->assertSeeInOrder(['div', 'class', 'favorite-count', '0', '/div']);

        $response = $this->actingAs($this->user)
            ->get('/item/favorite/2');

        $this->assertDatabaseHas('favorites', [
            'item_id' => $this->item->id,
            'user_id' => $this->user->id,
        ]);

        $response = $response->assertStatus(302);

        $response = $this->get('/item/' . $this->item->id);

        $response->assertSeeInOrder(['div', 'class', 'favorite-icon', 'favorite-added', '★', '/div']);
    }

    public function testCancellation()
    {
        $response = $this->get('/item/' . $this->item->id);

        $response->assertSeeInOrder(['div', 'class', 'favorite-count', '0', '/div']);

        $response = $this->actingAs($this->user)
            ->get('/item/favorite/3');

        $this->assertDatabaseHas('favorites', [
            'item_id' => $this->item->id,
            'user_id' => $this->user->id,
        ]);

        $response = $response->assertStatus(302);

        $response = $this->get('/item/' . $this->item->id);

        $response->assertSeeInOrder(['div', 'class', 'favorite-icon', 'favorite-added', '★', '/div']);

        $response = $this->actingAs($this->user)
            ->get('/item/favorite/3');

        $this->assertDatabaseMissing('favorites', [
            'item_id' => $this->item->id,
            'user_id' => $this->user->id,
        ]);

        $response = $response->assertStatus(302);

        $response = $this->get('/item/' . $this->item->id);

        $response->assertSeeInOrder(['div', 'class', 'favorite-icon', '★', '/div']);
    }
}
