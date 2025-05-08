<?php

namespace Tests\Feature;

use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use App\Models\Item;
use App\Models\User;

class ItemTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAllItems()
    {
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        $this->get('/')->assertSeeLivewire('grid');

        $items = [
            '腕時計',
            'HDD',
            '玉ねぎ3束',
            '革靴',
            'ノートPC',
            'マイク',
            'ショルダーバッグ',
            'タンブラー',
            'コーヒーミル',
            'メイクセット',
        ];

        Livewire::test('grid')->assertSeeInOrder($items);
    }

    public function testPurchasedItem()
    {
        Item::factory()->create([
            'name' => 'test',
            'purchased' => 1
        ]);

        $this->get('/')->assertSeeLivewire('grid');

        Livewire::test('grid', ['search' => 'test'])->assertSee('Sold');
    }

    public function testExhibitedItem()
    {
        $user = User::factory()->create();

        Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'test',
            'purchased' => 1
        ]);

        $this->get('/')->assertSeeLivewire('grid');

        Livewire::test('grid')->assertSee('test');

        Livewire::actingAs($user)->test('grid')->assertDontSee('test');
    }
}
