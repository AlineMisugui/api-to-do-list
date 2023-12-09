<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login_with_valid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => env('TEST_USER_EMAIL'),
            'password' => '12345678'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['user', 'token']);
    }

    public function test_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => env('TEST_USER_EMAIL')
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure(['error']);
    }
}
