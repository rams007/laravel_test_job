<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $userManager= User::create([
            'email'=>'test@gmail.com',
            'role'=>'manager',
            'password'=>Hash::make('123456')
        ]);

        User::create([
            'email'=>'test_employee@gmail.com',
            'password'=>Hash::make('123456'),
            'manager_id'=>$userManager->id
        ]);
    }
}
