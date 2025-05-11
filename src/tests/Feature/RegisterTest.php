<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function testValidateRequiredName()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);

        $email = "mail@mail.com";
        $password = "testtest";
        $passwordConfirmation = "testtest";

        $response = $this->post('/register', ['email' => $email, 'password' => $password, 'password-confirmation' => $passwordConfirmation]);

        $response->assertStatus(302);

        $this->get('/register')->assertSee('お名前を入力してください');
    }

    public function testValidateRequiredEmail()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);

        $name = "test";
        $password = "testtest";
        $passwordConfirmation = "testtest";

        $response = $this->post('/register', ['name' => $name, 'password' => $password, 'password-confirmation' => $passwordConfirmation]);

        $response->assertStatus(302);

        $this->get('/register')->assertSee('メールアドレスを入力してください');
    }

    public function testValidateRequiredPassword()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);

        $name = "test";
        $email = "mail@mail.com";
        $passwordConfirmation = "testtest";

        $response = $this->post('/register', ['name' => $name, 'email' => $email, 'password-confirmation' => $passwordConfirmation]);

        $response->assertStatus(302);

        $this->get('/register')->assertSee('パスワードを入力してください');
    }

    public function testValidatePasswordWordCount()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);

        $name = "test";
        $email = "mail@mail.com";
        $password = "1234567";
        $passwordConfirmation = "1234567";

        $response = $this->post('/register', ['name' => $name, 'email' => $email, 'password' => $password, 'password-confirmation' => $passwordConfirmation]);

        $response->assertStatus(302);

        $this->get('/register')->assertSee('パスワードは8文字以上で入力してください');
    }
    public function testValidatePasswordConfirmation()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);

        $name = "test";
        $email = "mail@mail.com";
        $password = "12345678";
        $passwordConfirmation = "123456789";

        $response = $this->post('/register', ['name' => $name, 'email' => $email, 'password' => $password, 'password-confirmation' => $passwordConfirmation]);

        $response->assertStatus(302);

        $this->get('/register')->assertSee('パスワードと一致しません');
    }

    public function testRegistrationComplete()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);

        $name = "test";
        $email = "mail@mail.com";
        $password = "12345678";
        $passwordConfirmation = "12345678";

        $response = $this->post('/register', ['name' => $name, 'email' => $email, 'password' => $password, 'password-confirmation' => $passwordConfirmation]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'name' => 'test',
            'email' => 'mail@mail.com',
        ]);

        $this->get('/email/verify')->assertSee('認証はこちら');
    }
}
