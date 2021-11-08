<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserRecord;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UserRecordsControllerTest extends TestCase
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
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_new_record()
    {
        $file = UploadedFile::fake()->image('image.jpg');
        $user = User::where('email', 'test_employee@gmail.com')->first();
        $response = $this->actingAs($user)->json('POST', '/employee/records', ['title' => 'Test', 'category_id' => '1', 'image' => $file]);
        $response->assertJson(['error' => false]);
        $this->assertDatabaseHas('user_records', [
            'title' => 'Test',
        ]);
        $response->assertStatus(200);
    }


    public function test_create_new_record_unauthorized()
    {
        $file = UploadedFile::fake()->image('image.jpg');
        $response = $this->json('POST', '/employee/records', ['title' => 'Test2', 'category_id' => '1', 'image' => $file]);
        $response->assertStatus(401);
    }

    public function test_delete_record()
    {

        $user = User::where('email', 'test_employee@gmail.com')->first();
        $userRecord = UserRecord::where('user_id', $user->id)->first();
        $response = $this->actingAs($user)->json('DELETE', '/employee/records/' . $userRecord->id);
        $response->assertJson(['error' => false]);
        $this->assertDatabaseMissing('user_records', [
            'id' => $userRecord->id,
        ]);
        $response->assertStatus(200);
    }

    public function test_delete_record_unauthorized()
    {

        $userRecord = UserRecord::first();
        $response = $this->json('DELETE', '/employee/records/' . $userRecord->id);
        $response->assertStatus(401);

    }

    public function test_delete_record_another_user()
    {

        $user = User::where('email', 'test_employee@gmail.com')->first();
        $userRecord = UserRecord::where('user_id', '!=', $user->id)->first();
        $response = $this->actingAs($user)->json('DELETE', '/employee/records/' . $userRecord->id);
        $response->assertStatus(403);
    }

}
