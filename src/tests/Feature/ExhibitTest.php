<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExhibitTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $user = User::factory()->create();

        $itemImage = UploadedFile::fake()->image('item.jpeg');

        $category = Category::factory()->create();

        $this->actingAs($user)
            ->get('/sell')
            ->assertStatus(200);

        $this->actingAs($user)
            ->post('/sell', [
                'image' => $itemImage,
                'categories' => $category->id,
                'condition' => 1,
                'name' => 'test',
                'brand' => 'test_brand',
                'description' => 'test_description',
                'price' => 1000,
            ])
            ->assertStatus(302);

        Storage::disk('public')->assertExists('image/item/' . $itemImage->hashName());

        $this->assertDatabaseHas('items', [
            'image_path' => 'public/image/item/' . $itemImage->hashName(),
            'condition' => 1,
            'name' => 'test',
            'brand' => 'test_brand',
            'description' => 'test_description',
            'price' => 1000,
        ]);

        $this->assertDatabaseHas('category_item', [
            'category_id' => $category->id,
            'item_id' => 1
        ]);

        $this->get('/item/1')
            ->assertStatus(200)
            ->assertSeeInOrder([
                '/image/item/' . $itemImage->hashName(),
                'test',
                'test_brand',
                '1,000',
                'test_description',
                $category->name,
                '良好',
            ]);
    }
}
