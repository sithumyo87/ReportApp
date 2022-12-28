<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // system
        $user = new User();
        $user->name = 'System';
        $user->email = 'system@gmail.com';
        $user->password =  Hash::make('nh123!@#');
        $user->save();
        $user->assignRole('system');

        // admin
        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@gmail.com';
        $user->password =  Hash::make('admin123');
        $user->save();
        $user->assignRole('admin');

        // moderator
        $user = new User();
        $user->name = 'moderator';
        $user->email = 'moderator@gmail.com';
        $user->password =  Hash::make('moderator123');
        $user->save();
        $user->assignRole('moderator');
    }
}
