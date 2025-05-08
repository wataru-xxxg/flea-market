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
        $user1 = User::factory()->create([
            'name' => 'test',
            'email' => 'mail@mail.com',
            'password' => bcrypt('testtest'),
            'email_verified_at' => date("Y-m-d H:i:s"),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'test',
            'email' => 'mail@mail.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'test2',
            'email' => 'mail2@mail.com',
            'password' => bcrypt('testtest'),
            'email_verified_at' => date("Y-m-d H:i:s"),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'test2',
            'email' => 'mail2@mail.com',
        ]);

        $item1 = Item::factory()->create([
            'user_id' => $user2->id,
            'name' => 'test1',
            'brand' => 'test1',
            'description' => 'test1',
            'image_path' => '/image/item/Test1.jpg',
            'condition' => 1,
            'price' => 1000,
            'purchased' => 0
        ]);

        $this->assertDatabaseHas('items', [
            'user_id' => $user2->id,
            'name' => 'test1',
            'brand' => 'test1',
            'description' => 'test1',
            'image_path' => '/image/item/Test1.jpg',
            'condition' => 1,
            'price' => 1000,
            'purchased' => 0
        ]);

        Item::factory()->create([
            'user_id' => $user2->id,
            'name' => 'test2',
            'brand' => 'test2',
            'description' => 'test2',
            'image_path' => '/image/item/Test2.jpg',
            'condition' => 2,
            'price' => 2000,
            'purchased' => 0
        ]);

        $this->assertDatabaseHas('items', [
            'user_id' => $user2->id,
            'name' => 'test2',
            'brand' => 'test2',
            'description' => 'test2',
            'image_path' => '/image/item/Test2.jpg',
            'condition' => 2,
            'price' => 2000,
            'purchased' => 0
        ]);

        Favorite::factory()->create([
            'item_id' => $item1->id,
            'user_id' => $user1->id,
        ]);

        $this->assertDatabaseHas('favorites', [
            'item_id' => $item1->id,
            'user_id' => $user1->id,
        ]);

        $this->get('/?page=mylist')->assertSeeLivewire('grid');

        Livewire::actingAs($user1)->test('grid', ['mylist' => true])
            ->assertSee('test1')
            ->assertDontSee('test2');
    }

    public function testPurchasedItem()
    {
        $user1 = User::factory()->create([
            'name' => 'test1',
            'email' => 'mail1@mail.com',
            'password' => bcrypt('testtest'),
            'email_verified_at' => date("Y-m-d H:i:s"),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'test1',
            'email' => 'mail1@mail.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'test2',
            'email' => 'mail2@mail.com',
            'password' => bcrypt('testtest'),
            'email_verified_at' => date("Y-m-d H:i:s"),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'test2',
            'email' => 'mail2@mail.com',
        ]);

        $item1 = Item::factory()->create([
            'user_id' => $user2->id,
            'name' => 'test1',
            'brand' => 'test1',
            'description' => 'test1',
            'image_path' => '/image/item/Test1.jpg',
            'condition' => 1,
            'price' => 1000,
            'purchased' => 1
        ]);

        $this->assertDatabaseHas('items', [
            'user_id' => $user2->id,
            'name' => 'test1',
            'brand' => 'test1',
            'description' => 'test1',
            'image_path' => '/image/item/Test1.jpg',
            'condition' => 1,
            'price' => 1000,
            'purchased' => 1
        ]);

        Item::factory()->create([
            'user_id' => $user2->id,
            'name' => 'test2',
            'brand' => 'test2',
            'description' => 'test2',
            'image_path' => '/image/item/Test2.jpg',
            'condition' => 2,
            'price' => 2000,
            'purchased' => 0
        ]);

        $this->assertDatabaseHas('items', [
            'user_id' => $user2->id,
            'name' => 'test2',
            'brand' => 'test2',
            'description' => 'test2',
            'image_path' => '/image/item/Test2.jpg',
            'condition' => 2,
            'price' => 2000,
            'purchased' => 0
        ]);

        Favorite::factory()->create([
            'item_id' => $item1->id,
            'user_id' => $user1->id,
        ]);

        $this->assertDatabaseHas('favorites', [
            'item_id' => $item1->id,
            'user_id' => $user1->id,
        ]);

        $this->get('/?page=mylist')->assertSeeLivewire('grid');

        Livewire::actingAs($user1)
            ->test('grid', ['mylist' => true])
            ->assertSee('test1')->assertDontSee('test2')
            ->assertSee('Sold');
    }

    public function testExhibitedItem()
    {
        $user1 = User::factory()->create([
            'name' => 'test1',
            'email' => 'mail1@mail.com',
            'password' => bcrypt('testtest'),
            'email_verified_at' => date("Y-m-d H:i:s"),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'test1',
            'email' => 'mail1@mail.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'test2',
            'email' => 'mail2@mail.com',
            'password' => bcrypt('testtest'),
            'email_verified_at' => date("Y-m-d H:i:s"),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'test2',
            'email' => 'mail2@mail.com',
        ]);

        $item1 = Item::factory()->create([
            'user_id' => $user1->id,
            'name' => 'test1',
            'brand' => 'test1',
            'description' => 'test1',
            'image_path' => '/image/item/Test1.jpg',
            'condition' => 1,
            'price' => 1000,
            'purchased' => 0
        ]);

        $this->assertDatabaseHas('items', [
            'user_id' => $user1->id,
            'name' => 'test1',
            'brand' => 'test1',
            'description' => 'test1',
            'image_path' => '/image/item/Test1.jpg',
            'condition' => 1,
            'price' => 1000,
            'purchased' => 0
        ]);

        $item2 = Item::factory()->create([
            'user_id' => $user2->id,
            'name' => 'test2',
            'brand' => 'test2',
            'description' => 'test2',
            'image_path' => '/image/item/Test2.jpg',
            'condition' => 2,
            'price' => 2000,
            'purchased' => 0
        ]);

        $this->assertDatabaseHas('items', [
            'user_id' => $user2->id,
            'name' => 'test2',
            'brand' => 'test2',
            'description' => 'test2',
            'image_path' => '/image/item/Test2.jpg',
            'condition' => 2,
            'price' => 2000,
            'purchased' => 0
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
        Item::factory()->create([
            'user_id' => 1,
            'name' => 'test',
            'brand' => 'test',
            'description' => 'test',
            'image_path' => '/image/item/Test.jpg',
            'condition' => 1,
            'price' => 1000,
            'purchased' => 0
        ]);

        $this->assertDatabaseHas('items', [
            'user_id' => 1,
            'name' => 'test',
            'brand' => 'test',
            'description' => 'test',
            'image_path' => '/image/item/Test.jpg',
            'condition' => 1,
            'price' => 1000,
            'purchased' => 0
        ]);

        $this->get('/')->assertSeeLivewire('grid');

        Livewire::test('grid')->assertSee('test');

        $this->get('/?page=mylist')->assertSeeLivewire('grid');

        Livewire::test('grid', ['mylist' => true])->assertDontSee('test');
    }
}
