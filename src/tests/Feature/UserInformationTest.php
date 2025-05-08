<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use Tests\TestCase;

class UserInformationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGet()
    {
        $user = User::factory()->create(['name' => 'test_user',]);

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'imagePath' => 'test_path'
        ]);

        $exhibitedItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'exhibitedItem'
        ]);

        $purchasedItem = Item::factory()->create([
            'name' => 'purchasedItem',
            'purchased' => 1
        ]);

        Purchase::factory()->create([
            'item_id' => $purchasedItem->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->get('/mypage')
            ->assertStatus(200)
            ->assertSeeInOrder([$profile->imagePath, $user->name]);

        $this->actingAs($user)
            ->get('/mypage/?page=sell')
            ->assertSee($exhibitedItem->name);

        $this->actingAs($user)
            ->get('/mypage/?page=buy')
            ->assertSee($purchasedItem->name);
    }

    public function testChange()
    {
        $user = User::factory()->create(['name' => 'test_user',]);

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'imagePath' => 'test_path'
        ]);

        $this->actingAs($user)
            ->get('/mypage/profile')
            ->assertStatus(200)
            ->assertSeeInOrder([
                $profile->imagePath,
                $user->name,
                $profile->postCode,
                $profile->address,
                $profile->building
            ]);
    }
}
