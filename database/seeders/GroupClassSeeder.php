<?php

namespace Database\Seeders;

use App\Models\GroupClass;
use App\Models\GroupClassDay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::where('email', 'teacher@gmail.com')->first();
        if ($user) {
            $class = GroupClass::create([
                'user_id' => $user->id,
                'title' => 'French for Beginners (A1)',
                'duration_per_class' => 60,
                'lessons_per_week' => 2,
                'max_students' => 8,
                'price_per_student' => 50.00,
                'is_active' => true,
            ]);

            $days = ['Mon', 'Tue', 'Thu'];

            foreach ($days as $day) {
                GroupClassDay::create([
                    'group_class_id' => $class->id,
                    'day' => $day,
                ]);
            }
        }
    }
}
