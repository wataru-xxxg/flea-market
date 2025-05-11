<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $item;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->item = Item::factory()->create();
    }

    public function testCanComment()
    {
        $response = $this->actingAs($this->user)
            ->post('/item/comment/' . $this->item->id, ['comment' => 'test_comment']);

        $response->assertStatus(302);

        $this->assertDatabaseHas(
            'comments',
            [
                'item_id' => $this->item->id,
                'user_id' => $this->user->id,
                'comment' => 'test_comment',
            ]
        );
    }

    public function testCantComment()
    {
        $response = $this->post('/item/comment/' . $this->item->id, ['comment' => 'test_comment']);

        $response->assertStatus(302);

        $this->assertDatabaseMissing(
            'comments',
            [
                'item_id' => $this->item->id,
                'user_id' => $this->user->id,
                'comment' => 'test_comment',
            ]
        );
    }

    public function testValidateEmptyComment()
    {
        $response = $this->actingAs($this->user)
            ->post('/item/comment/' . $this->item->id, ['comment' => '']);

        $response->assertStatus(302);

        $this->get('/item/' . $this->item->id)->assertSee('コメントを入力してください');
    }

    public function testValidateCommentCount()
    {
        $comment = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

        $this->assertEquals(256, strlen($comment));

        $response = $this->actingAs($this->user)
            ->post('/item/comment/' . $this->item->id, ['comment' => $comment]);

        $response->assertStatus(302);

        $this->get('/item/' . $this->item->id)->assertSee('コメントは255文字以内で入力してください');
    }
}
