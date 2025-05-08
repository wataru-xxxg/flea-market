<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testValidateEmail()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);

        $password = "testtest";

        $response = $this->post('/login', ['password' => $password]);

        $response->assertStatus(302);

        $this->get('/login')->assertSee('メールアドレスを入力してください');
    }

    public function testValidatePassword()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);

        $email = "mail@mail.com";

        $response = $this->post('/login', ['email' => $email]);

        $response->assertStatus(302);

        $this->get('/login')->assertSee('パスワードを入力してください');
    }

    public function testValidateUnregistered()
    {
        User::factory()->create([
            'name' => 'test',
            'email' => 'mail@mail.com',
            'password' => bcrypt('testtest')
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'test',
            'email' => 'mail@mail.com',
        ]);

        $response = $this->get('/login');

        $response->assertStatus(200);

        $email = "aaa@bbb.com";
        $password = "12345678";

        $response = $this->post('/login', ['email' => $email, 'password' => $password]);

        $response->assertStatus(302);

        $this->get('/login')->assertSee('ログイン情報が登録されていません');
    }

    public function testLoginComplete()
    {
        User::factory()->create([
            'name' => 'test',
            'email' => 'mail@mail.com',
            'password' => bcrypt('testtest')
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'test',
            'email' => 'mail@mail.com',
        ]);

        $response = $this->get('/login');

        $response->assertStatus(200);

        $email = "mail@mail.com";
        $password = "testtest";

        $response = $this->post('/login', ['email' => $email, 'password' => $password]);

        $response->assertStatus(302);

        $this->assertAuthenticated();
    }
}
