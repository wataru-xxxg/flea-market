<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFavoriteButton()
    {
        $user = User::factory()->create([
            'name' => 'test_user',
            'email' => 'mail@mail.com',
            'password' => bcrypt('testtest'),
            'email_verified_at' => date("Y-m-d H:i:s"),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'test_user',
            'email' => 'mail@mail.com',
        ]);

        $item = Item::factory()->create([
            'user_id' => 1,
            'name' => 'test',
            'brand' => 'test_brand',
            'description' => 'test_description',
            'image_path' => '/image/item/Test.jpg',
            'condition' => 1,
            'price' => 2000,
            'purchased' => 0
        ]);

        $this->assertDatabaseHas('items', [
            'user_id' => 1,
            'name' => 'test',
            'brand' => 'test_brand',
            'description' => 'test_description',
            'image_path' => '/image/item/Test.jpg',
            'condition' => 1,
            'price' => 2000,
            'purchased' => 0
        ]);

        $response = $this->actingAs($user)
            ->get('/item/favorite/1');

        $this->assertDatabaseHas('favorites', [
            'item_id' => $user->id,
            'user_id' => $item->id,
        ]);

        $response = $response->assertStatus(302);

        $response = $this->get('/item/1');

        $response->assertSeeInOrder(['div', 'class', 'favorite-count', '1', '/div']);
    }

    public function testIconColor()
    {
        $user = User::factory()->create([
            'name' => 'test_user',
            'email' => 'mail@mail.com',
            'password' => bcrypt('testtest'),
            'email_verified_at' => date("Y-m-d H:i:s"),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'test_user',
            'email' => 'mail@mail.com',
        ]);

        $item = Item::factory()->create([
            'user_id' => 1,
            'name' => 'test',
            'brand' => 'test_brand',
            'description' => 'test_description',
            'image_path' => '/image/item/Test.jpg',
            'condition' => 1,
            'price' => 2000,
            'purchased' => 0
        ]);

        $this->assertDatabaseHas('items', [
            'user_id' => 1,
            'name' => 'test',
            'brand' => 'test_brand',
            'description' => 'test_description',
            'image_path' => '/image/item/Test.jpg',
            'condition' => 1,
            'price' => 2000,
            'purchased' => 0
        ]);

        $response = $this->get('/item/2');

        $response->assertSeeInOrder(['div', 'class', 'favorite-count', '0', '/div']);

        $response = $this->actingAs($user)
            ->get('/item/favorite/2');

        $this->assertDatabaseHas('favorites', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $response = $response->assertStatus(302);

        $response = $this->get('/item/2');

        $response->assertSeeInOrder(['div', 'class', 'favorite-icon', 'favorite-added', '★', '/div']);
    }

    public function testCancellation()
    {
        $user = User::factory()->create([
            'name' => 'test_user',
            'email' => 'mail@mail.com',
            'password' => bcrypt('testtest'),
            'email_verified_at' => date("Y-m-d H:i:s"),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'test_user',
            'email' => 'mail@mail.com',
        ]);

        $item = Item::factory()->create([
            'user_id' => 1,
            'name' => 'test',
            'brand' => 'test_brand',
            'description' => 'test_description',
            'image_path' => '/image/item/Test.jpg',
            'condition' => 1,
            'price' => 2000,
            'purchased' => 0
        ]);

        $this->assertDatabaseHas('items', [
            'user_id' => 1,
            'name' => 'test',
            'brand' => 'test_brand',
            'description' => 'test_description',
            'image_path' => '/image/item/Test.jpg',
            'condition' => 1,
            'price' => 2000,
            'purchased' => 0
        ]);

        $response = $this->get('/item/3');

        $response->assertSeeInOrder(['div', 'class', 'favorite-count', '0', '/div']);

        $response = $this->actingAs($user)
            ->get('/item/favorite/3');

        $this->assertDatabaseHas('favorites', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $response = $response->assertStatus(302);

        $response = $this->get('/item/3');

        $response->assertSeeInOrder(['div', 'class', 'favorite-icon', 'favorite-added', '★', '/div']);

        $response = $this->actingAs($user)
            ->get('/item/favorite/3');

        $this->assertDatabaseMissing('favorites', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $response = $response->assertStatus(302);

        $response = $this->get('/item/3');

        $response->assertSeeInOrder(['div', 'class', 'favorite-icon', '★', '/div']);
    }
}
