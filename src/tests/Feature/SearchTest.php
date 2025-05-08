<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Favorite;
use Livewire\Livewire;

class SearchTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPartialMatch()
    {
        Item::factory()->create(['name' => 'test1']);
        Item::factory()->create(['name' => 'test2']);

        $this->get('/')->assertSeeLivewire('grid');

        Livewire::test('grid', ['search' => '1'])
            ->assertSee('test1')
            ->assertDontSee('test2');
    }

    public function testMylistPartialMatch()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $item1 = Item::factory()->create([
            'user_id' => $user2->id,
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

        Favorite::factory()->create([
            'item_id' => $item2->id,
            'user_id' => $user1->id,
        ]);

        $this->get('/?page=mylist')->assertSeeLivewire('grid');

        Livewire::actingAs($user1)
            ->test('grid', ['mylist' => true, 'search' => '1'])
            ->assertSee('test1')
            ->assertDontSee('test2');
    }
}
