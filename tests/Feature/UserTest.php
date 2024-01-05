<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testRegisterSuccess()
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

    public function testRegisterFailed()
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

    public function testUsernameAlreadyExists()
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
                    'username' => ['The username has already been taken.'],
                ],
            ]);
    }

    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/users/login', [
            'username' => 'testing',
            'password' => 'testing',
        ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'testing',
                    'name' => 'testing',
                ],
            ]);

        $user = User::where('username', 'testing')->first();
        self::assertNotnull($user->token);
    }

    public function testLoginFailedUsernameNotFound()
    {
        $this->post('/api/users/login', [
            'username' => 'testi',
            'password' => 'testing',
        ])
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => ['Invalid username or password'],
                ],
            ]);
    }

    public function testLoginFailedPasswordWrong()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'testing',
            'password' => 'salah',
        ])
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => ['username or password wrong'],
                ],
            ]);
    }
}
