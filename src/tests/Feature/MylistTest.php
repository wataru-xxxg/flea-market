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
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFavoriteItem()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create(['name' => 'test1']);

        Item::factory()->create(['name' => 'test2']);

        Favorite::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('favorites', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $this->get('/?page=mylist')->assertSeeLivewire('grid');

        Livewire::actingAs($user)->test('grid', ['mylist' => true])
            ->assertSee('test1')
            ->assertDontSee('test2');
    }

    public function testPurchasedItem()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'name' => 'test1',
            'purchased' => 1
        ]);

        Item::factory()->create(['name' => 'test2',]);

        Favorite::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('favorites', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $this->get('/?page=mylist')->assertSeeLivewire('grid');

        Livewire::actingAs($user)
            ->test('grid', ['mylist' => true])
            ->assertSee('test1')->assertDontSee('test2')
            ->assertSee('Sold');
    }

    public function testExhibitedItem()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $item1 = Item::factory()->create([
            'user_id' => $user1->id,
            'name' => 'test1',
        ]);

        $item2 = Item::factory()->create([
            'user_id' => $user2->id,
            'name' => 'test2',
        ]);

        Favorite::factory()->create([
            'item_id' => $item1->id,
            'user_id' => $user1->id,
        ]);

        $this->assertDatabaseHas('favorites', [
            'item_id' => $item1->id,
            'user_id' => $user1->id,
        ]);

        Favorite::factory()->create([
            'item_id' => $item2->id,
            'user_id' => $user1->id,
        ]);

        $this->assertDatabaseHas('favorites', [
            'item_id' => $item2->id,
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
