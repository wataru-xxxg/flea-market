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

        $user2ExhibitedItem1 = Item::factory()->create([
            'user_id' => $user2->id,
            'name' => 'user2ExhibitedItem1',
        ]);

        $user2ExhibitedItem2 = Item::factory()->create([
            'user_id' => $user2->id,
            'name' => 'user2ExhibitedItem2',
        ]);

        Favorite::factory()->create([
            'item_id' => $user2ExhibitedItem1->id,
            'user_id' => $user1->id,
        ]);

        Favorite::factory()->create([
            'item_id' => $user2ExhibitedItem2->id,
            'user_id' => $user1->id,
        ]);

        $this->get('/?page=mylist')->assertSeeLivewire('grid');

        Livewire::actingAs($user1)
            ->test('grid', ['mylist' => true, 'search' => '1'])
            ->assertSee('user2ExhibitedItem1')
            ->assertDontSee('user2ExhibitedItem12');
    }
}
