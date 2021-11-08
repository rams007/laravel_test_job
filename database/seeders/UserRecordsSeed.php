<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRecord;
use Illuminate\Database\Seeder;

class UserRecordsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userManager = User::whereNotNull('manager_id')->first();
        UserRecord::create([
            'user_id' => $userManager->id,
            'title' => 'Test 1',
            'image_path' => '/images/TI0shcWOGf2PLOF6YbOVaRHGdXtDsVUTTkAKoi8P.png',
            'category_id' => 1
        ]);
        UserRecord::create([
            'user_id' => $userManager->id,
            'title' => 'Test 2',
            'image_path' => '/images/TI0shcWOGf2PLOF6YbOVaRHGdXtDsVUTTkAKoi8P.png',
            'category_id' => 1
        ]);

    }
}
