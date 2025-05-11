<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Comment;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    public function testShowRequiredInformation()
    {
        $user = User::factory()->create(['name' => 'test_user']);

        $item = Item::factory()->create([
            'user_id' => 1,
            'name' => 'test',
            'brand' => 'test_brand',
            'description' => 'test_description',
            'imagePath' => '/image/item/Test.jpg',
            'condition' => 1,
            'price' => 1000,
            'purchased' => 0
        ]);

        $category = Category::factory()->create([
            'name' => 'test_category',
        ]);

        DB::table('category_item')->insert([
            'category_id' => $category->id,
            'item_id' => $item->id
        ]);

        Favorite::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' => 'test_comment'
        ]);

        $this->get('/item/1')
            ->assertStatus(200)
            ->assertSeeInOrder([
                '/image/item/Test.jpg',
                'test',
                'test_brand',
                '1',
                '1',
                'test_description',
                'test_category',
                '良好',
                'コメント(',
                '1',
                ')',
                'test_user',
                'test_comment'
            ]);
    }

    public function testMultipleCategories()
    {
        $item = Item::factory()->create([
            'user_id' => 1,
            'name' => 'test',
            'brand' => 'test_brand',
            'description' => 'test_description',
            'imagePath' => '/image/item/Test.jpg',
            'condition' => 1,
            'price' => 1000,
            'purchased' => 0
        ]);

        $category1 = Category::factory()->create([
            'name' => 'test_category1',
        ]);

        $category2 = Category::factory()->create([
            'name' => 'test_category2',
        ]);

        DB::table('category_item')->insert([
            'category_id' => $category1->id,
            'item_id' => $item->id
        ]);

        DB::table('category_item')->insert([
            'category_id' => $category2->id,
            'item_id' => $item->id
        ]);

        $this->get('/item/2')
            ->assertStatus(200)
            ->assertSeeInOrder([
                'test_category1',
                'test_category2',
            ]);
    }
}
