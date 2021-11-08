<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
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
     * Try creae employee
     *
     * @return void
     */
    public function test_create_employee()
    {
        $user = User::where('email', 'test@gmail.com')->first();
        $response = $this->actingAs($user)->json('POST', '/employee', ['email' => 'test3@gmail.com', 'password' => '123456']);
        $response->assertJson(['error' => false]);
        $this->assertDatabaseHas('users', [
            'email' => 'test3@gmail.com',
        ]);
    }

    public function test_create_employee_unauthorized()
    {

        $response = $this->json('POST', '/employee', ['email' => 'test3@gmail.com', 'password' => '123456']);
        $response->assertStatus(401);
    }

    public function test_delete_employee()
    {
        $user = User::where('email', 'test@gmail.com')->first();
        $employee = User::where('email', 'test_employee@gmail.com')->first();
        $response = $this->actingAs($user)->json('DELETE', '/employee/'.$employee->id);
        $response->assertJson(['error' => false]);
        $this->assertDatabaseMissing('users', [
            'email' => 'test_employee@gmail.com',
        ]);
    }

    public function test_delete_not_existed_employee()
    {
        $user = User::where('email', 'test@gmail.com')->first();
        $response = $this->actingAs($user)->json('DELETE', '/employee/15');
        $response->assertStatus(404);
    }

    public function test_delete_employee_unautorized()
    {
        $employee = User::where('email', 'test_employee@gmail.com')->first();
        $response = $this->json('DELETE', '/employee/'.$employee->id);
        $response->assertStatus(401);
    }

}
