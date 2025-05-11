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

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['name' => 'test_user',]);
    }
    public function testGet()
    {
        $profile = Profile::factory()->create([
            'user_id' => $this->user->id,
            'imagePath' => 'test_path'
        ]);

        $exhibitedItem = Item::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'exhibitedItem'
        ]);

        $purchasedItem = Item::factory()->create([
            'name' => 'purchasedItem',
            'purchased' => 1
        ]);

        Purchase::factory()->create([
            'item_id' => $purchasedItem->id,
            'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->user)
            ->get('/mypage')
            ->assertStatus(200)
            ->assertSeeInOrder([$profile->imagePath, $this->user->name]);

        $this->actingAs($this->user)
            ->get('/mypage/?page=sell')
            ->assertSee($exhibitedItem->name);

        $this->actingAs($this->user)
            ->get('/mypage/?page=buy')
            ->assertSee($purchasedItem->name);
    }

    public function testChange()
    {
        $profile = Profile::factory()->create([
            'user_id' => $this->user->id,
            'imagePath' => 'test_path'
        ]);

        $this->actingAs($this->user)
            ->get('/mypage/profile')
            ->assertStatus(200)
            ->assertSeeInOrder([
                $profile->imagePath,
                $this->user->name,
                $profile->postCode,
                $profile->address,
                $profile->building
            ]);
    }
}
