<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        // seed the database
        $this->artisan('db:seed');
        // alternatively you can call
        // $this->seed();
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_get_unauthorized()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    public function test_get_authorized()
    {
        $user = User::where('email', 'test@gmail.com')->first();
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
    }

    public function test_login()
    {
        $response = $this->post('/login', ['email' => 'test@gmail.com',
            'password' => '123456']);
        $response->assertStatus(302);
        $response->assertLocation('/');
    }

    public function test_register()
    {
        $response = $this->post('/register', ['email' => 'test2@gmail.com',
            'password' => '123456']);
        $response->assertStatus(302);
        $response->assertLocation('/');
        $this->assertDatabaseHas('users', [
            'email' => 'test2@gmail.com',
        ]);
    }

    public function test_logout()
    {
        $user = User::where('email', 'test@gmail.com')->first();
        $response = $this->actingAs($user)->get('/logout');
        $response->assertStatus(302);
        $response->assertLocation('/login');

    }
}
