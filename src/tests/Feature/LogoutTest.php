<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLogoutComplete()
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

        $response = $this->post('/logout');

        $response->assertStatus(302);

        $this->assertGuest();
    }
}
