<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'name',
            'email' => 'email@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'phone' => '01956874125',
            'role' => 'admin'
        ]);

        $response->assertStatus(201);
    }

    /**
     *login test
     */
    public function test_login()
    {
        User::factory()->create([
            'name' => 'name',
            'email' => 'email@gmail.com',
            'password' => '12345678',
            'phone' => '01956874125',
            'role' => 'admin'
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'email@gmail.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(200);
    }
}
