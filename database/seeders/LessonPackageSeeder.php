<?php

namespace Database\Seeders;

use App\Models\LessonPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = \App\Models\User::where('email', 'teacher@gmail.com')->first();

        if ($user) {
            LessonPackage::insert([
                [
                    'user_id' => $user->id,
                    'name' => 'Package 1',
                    'number_of_lessons' => 5,
                    'duration_per_lesson' => 60,
                    'price' => 80.00,
                    'is_active' => true,
                ],
                [
                    'user_id' => $user->id,
                    'name' => 'Package 2',
                    'number_of_lessons' => 10,
                    'duration_per_lesson' => 60,
                    'price' => 150.00,
                    'is_active' => true,
                ],
            ]);
        }
    }
}
