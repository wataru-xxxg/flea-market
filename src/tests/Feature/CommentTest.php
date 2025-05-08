<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCanComment()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->post('/item/comment/1', ['comment' => 'test_comment']);

        $response->assertStatus(302);

        $this->assertDatabaseHas(
            'comments',
            [
                'item_id' => $item->id,
                'user_id' => $user->id,
                'comment' => 'test_comment',
            ]
        );
    }

    public function testCantComment()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $response = $this->post('/item/comment/2', ['comment' => 'test_comment']);

        $response->assertStatus(302);

        $this->assertDatabaseMissing(
            'comments',
            [
                'item_id' => $item->id,
                'user_id' => $user->id,
                'comment' => 'test_comment',
            ]
        );
    }

    public function testValidateEmptyComment()
    {
        $user = User::factory()->create();

        Item::factory()->create();

        $response = $this->actingAs($user)
            ->post('/item/comment/3', ['comment' => '']);

        $response->assertStatus(302);

        $this->get('/item/3')->assertSee('コメントを入力してください');
    }

    public function testValidateCommentCount()
    {
        $user = User::factory()->create();

        Item::factory()->create();

        $comment = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

        $this->assertEquals(256, strlen($comment));

        $response = $this->actingAs($user)
            ->post('/item/comment/4', ['comment' => $comment]);

        $response->assertStatus(302);

        $this->get('/item/4')->assertSee('コメントは255文字以内で入力してください');
    }
}
