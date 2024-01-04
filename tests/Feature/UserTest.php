<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testRegisterSuccess(): void
    {
        $this->post('/api/users', [
            'username' => 'arjunstein',
            'password' => 'rahasia',
            'name' => 'Arjun Gunawan',
        ])
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'username' => 'arjunstein',
                    'name' => 'Arjun Gunawan',
                ],
            ]);
    }

    public function testRegisterFailed(): void
    {
        $this->post('/api/users', [
            'username' => '',
            'password' => '',
            'name' => '',
        ])
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'username' => ['Username cannot be empty.'],
                    'password' => ['Password cannot be empty.'],
                    'name' => ['Name cannot be empty.'],
                ],
            ]);
    }

    public function testUsernameAlreadyExists(): void
    {
        $this->testRegisterSuccess();
        $this->post('/api/users', [
            'username' => 'arjunstein',
            'password' => 'rahasia',
            'name' => 'Arjun Gunawan',
        ])
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'username' => [
                        'The username has already been taken.',
                    ]
                ],
            ]);
    }
}
