<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::insert([
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'status' => 'active',
                'is_verified' => 1,
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'teacher',
                'email' => 'teacher@gmail.com',
                'role' => 'teacher',
                'is_verified' => 1,
                'status' => 'active',
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'student',
                'email' => 'student@gmail.com',
                'role' => 'student',
                'is_verified' => 1,
                'status' => 'active',
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Language::insert([
            [
                'name' => 'German',
                'symbol' => 'DE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'English',
                'symbol' => 'EN',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
