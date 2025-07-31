<?php

namespace Database\Seeders;

use App\Models\LessonSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::where('email', 'teacher@gmail.com')->first();

        if ($user) {
            LessonSetting::create([
                'user_id' => $user->id,
                'duration_30' => 10.00,
                'duration_60' => 18.00,
                'duration_90' => 25.00,
                'duration_120' => 30.00,
            ]);
        }
    }
}
