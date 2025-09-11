<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\SettingsRequest;
use App\Models\TeacherSetting;
use App\Models\LessonPackage;
use App\Models\GroupClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
public function index()
{
    $teacher = Auth::user();

    // Fetch teacher settings
    $settings = TeacherSetting::where('teacher_id', $teacher->id)
        ->pluck('value', 'key')
        ->toArray();

    // Fetch lesson packages
    $packages = LessonPackage::where('teacher_id', $teacher->id)
        ->orderBy('id')
        ->get()
        ->keyBy('package_number')
        ->toArray();

    // Fetch group classes with days
    $groups = GroupClass::where('teacher_id', $teacher->id)
        ->with('days')
        ->get()
        ->map(function ($group) {
            // Ensure features is always an array
            $features = $group->features;
            if (is_string($features)) {
                $features = array_map('trim', explode(',', $features));
            }
            if (!is_array($features)) {
                $features = [];
            }

            // Normalize days and times as strings
            $days = [];
            $times = [];
            foreach ($group->days as $d) {
                $days[] = $d->day instanceof \Carbon\Carbon
                    ? $d->day->format('Y-m-d')
                    : (string) $d->day;

                $times[] = $d->time ?? '';
            }

            return [
                'title'             => $group->title,
                'description'       => $group->description,
                'duration_per_class'=> $group->duration_per_class,
                'lessons_per_week'  => $group->lessons_per_week,
                'max_students'      => $group->max_students,
                'features'          => $features,
                'price_per_student' => $group->price_per_student,
                'is_active'         => $group->is_active,
                'days'              => $days,
                'times'             => $times,
            ];
        })
        ->toArray(); // 👈 convert collection to plain array for Blade

    return view('teacher.content.profile.settings', compact('settings', 'packages', 'groups'));
}



    public function update(SettingsRequest $request)
{
    DB::beginTransaction();
    $teacher = Auth::user();

    $this->updateIndividualPricing($teacher->id, $request->validated());
    $this->updateLessonPackages($teacher->id, $request->validated());
    
    $this->updateGroupClasses($teacher->id, $request->validated());

    DB::commit();
}


    // private function updateIndividualPricing($teacherId, array $data)
    // {
    //     $durations = [30, 60, 90, 120];

    //     foreach ($durations as $duration) {
    //         $key = "duration_{$duration}";
    //         if (isset($data[$key])) {
    //             TeacherSetting::updateOrCreate(
    //                 ['teacher_id' => $teacherId, 'key' => $key],
    //                 ['value' => $data[$key]]
    //             );
    //         }
    //     }
    // }
    private function updateIndividualPricing($teacherId, array $data)
    {
        $durations = [60]; // Only 60 minutes now

        foreach ($durations as $duration) {
            $key = "duration_{$duration}";
            if (isset($data[$key])) {
                TeacherSetting::updateOrCreate(
                    ['teacher_id' => $teacherId, 'key' => $key],
                    ['value' => $data[$key]]
                );
            }
        }
    }

    private function updateLessonPackages($teacherId, array $data)
    {
        if (!isset($data['packages'])) {
            return;
        }

        LessonPackage::where('teacher_id', $teacherId)->delete();

        foreach ($data['packages'] as $packageNumber => $packageData) {
            if (isset($packageData['number_of_lessons']) && !empty($packageData['price'])) {
                LessonPackage::create([
                    'teacher_id' => $teacherId,
                    'package_number' => $packageNumber,
                    'name' => $packageData['name'],
                    'number_of_lessons' => $packageData['number_of_lessons'],
                    'duration_per_lesson' => $packageData['duration_per_lesson'],
                    'price' => $packageData['price'],
                    'is_active' => isset($packageData['is_active']) ? 1 : 0,
                ]);
            }
        }
    }

private function updateGroupClasses($teacherId, array $data)
{
    if (!isset($data['groups'])) {
        return;
    }

    // Remove old groups for the teacher
    GroupClass::where('teacher_id', $teacherId)->delete();

    foreach ($data['groups'] as $groupData) {
        if (!empty($groupData['title'])) {
            $maxStudents = isset($groupData['max_students']) ? (int) $groupData['max_students'] : 1;
            $maxStudents = max(1, min($maxStudents, 100));

            // Clean description
            $description = isset($groupData['description'])
                ? strip_tags(substr(trim($groupData['description']), 0, 500))
                : null;

            // Normalize features
            $features = [];
            if (!empty($groupData['features'])) {
                $features = is_array($groupData['features'])
                    ? $groupData['features']
                    : array_map('trim', explode(',', $groupData['features']));
            }

            // Create GroupClass
            $group = GroupClass::create([
                'teacher_id'        => $teacherId,
                'title'             => $groupData['title'],
                'description'       => $description,
                'duration_per_class'=> $groupData['duration_per_class'] ?? 60,
                'lessons_per_week'  => $groupData['lessons_per_week'] ?? 1,
                'max_students'      => $maxStudents,
                'price_per_student' => $groupData['price_per_student'] ?? 0,
                'features'          => json_encode($features), // ✅ safer for DB text
                'is_active'         => isset($groupData['is_active']) ? 1 : 0,
            ]);


            // Save days + times (match index, not value)
            if (isset($groupData['days'], $groupData['times']) &&
                is_array($groupData['days']) && is_array($groupData['times'])) {

               foreach ($groupData['days'] as $i => $day) {
    $time = $groupData['times'][$i] ?? null;
    $group->days()->create([
        'day' => $day,
        'time' => $time
    ]);
}

            }
        }
    }
    
}

}