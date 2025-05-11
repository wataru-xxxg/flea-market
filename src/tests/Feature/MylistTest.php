<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Favorite;
use Livewire\Livewire;

class MylistTest extends TestCase
{
    use RefreshDatabase;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }
    public function testFavoriteItem()
    {
        $favoriteItem = Item::factory()->create(['name' => 'favoriteItem']);

        Item::factory()->create(['name' => 'test']);

        Favorite::factory()->create([
            'item_id' => $favoriteItem->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('favorites', [
            'item_id' => $favoriteItem->id,
            'user_id' => $this->user->id,
        ]);

        $this->get('/?page=mylist')->assertSeeLivewire('grid');

        Livewire::actingAs($this->user)->test('grid', ['mylist' => true])
            ->assertSee('favoriteItem')
            ->assertDontSee('test');
    }

    public function testPurchasedItem()
    {
        $purchasedItem = Item::factory()->create([
            'name' => 'purchasedItem',
            'purchased' => 1
        ]);

        Item::factory()->create(['name' => 'notPurchasedItem',]);

        Favorite::factory()->create([
            'item_id' => $purchasedItem->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('favorites', [
            'item_id' => $purchasedItem->id,
            'user_id' => $this->user->id,
        ]);

        $this->get('/?page=mylist')->assertSeeLivewire('grid');

        Livewire::actingAs($this->user)
            ->test('grid', ['mylist' => true])
            ->assertSee('purchasedItem')
            ->assertDontSee('notPurchasedItem')
            ->assertSee('Sold');
    }

    public function testExhibitedItem()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $user1ExhibitedItem = Item::factory()->create([
            'user_id' => $user1->id,
            'name' => 'test1',
        ]);

        $user2ExhibitedItem = Item::factory()->create([
            'user_id' => $user2->id,
            'name' => 'test2',
        ]);

        Favorite::factory()->create([
            'item_id' => $user1ExhibitedItem->id,
            'user_id' => $user1->id,
        ]);

        $this->assertDatabaseHas('favorites', [
            'item_id' => $user1ExhibitedItem->id,
            'user_id' => $user1->id,
        ]);

        Favorite::factory()->create([
            'item_id' => $user2ExhibitedItem->id,
            'user_id' => $user1->id,
        ]);

        $this->assertDatabaseHas('favorites', [
            'item_id' => $user2ExhibitedItem->id,
            'user_id' => $user1->id,
        ]);

        $this->get('/?page=mylist')->assertSeeLivewire('grid');

        Livewire::actingAs($user1)
            ->test('grid', ['mylist' => true])
            ->assertSee('test2')->assertDontSee('test1');
    }

    public function testUnauthenticated()
    {
        Item::factory()->create(['name' => 'test']);

        $this->get('/')->assertSeeLivewire('grid');

        Livewire::test('grid')->assertSee('test');

        $this->get('/?page=mylist')->assertSeeLivewire('grid');

        Livewire::test('grid', ['mylist' => true])->assertDontSee('test');
    }
}
