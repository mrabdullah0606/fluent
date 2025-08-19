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
        $settings = TeacherSetting::where('teacher_id', $teacher->id)
            ->pluck('value', 'key')
            ->toArray();
        $packages = LessonPackage::where('teacher_id', $teacher->id)
            ->orderBy('id')
            ->get()
            ->keyBy('package_number')
            ->toArray();
        $groups = GroupClass::where('teacher_id', $teacher->id)
            ->with('days')
            ->get()
            ->map(function ($group) {
                return [
                    'title' => $group->title,
                    'description' => $group->description, // Add description field
                    'duration_per_class' => $group->duration_per_class,
                    'lessons_per_week' => $group->lessons_per_week,
                    'max_students' => $group->max_students,
                    'price_per_student' => $group->price_per_student,
                    'is_active' => $group->is_active,
                    'days' => $group->days->pluck('day')->toArray()
                ];
            });

        return view('teacher.content.profile.settings', compact('settings', 'packages', 'groups'));
    }

    public function update(SettingsRequest $request)
    {
        try {
            DB::beginTransaction();

            $teacher = Auth::user();

            $this->updateIndividualPricing($teacher->id, $request->validated());
            $this->updateLessonPackages($teacher->id, $request->validated());
            $this->updateGroupClasses($teacher->id, $request->validated());

            DB::commit();

            return redirect()->route('teacher.settings.index')
                ->with('success', 'Settings updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update settings. Please try again.');
        }
    }

    private function updateIndividualPricing($teacherId, array $data)
    {
        $durations = [30, 60, 90, 120];

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
            if (!empty($packageData['number_of_lessons']) && !empty($packageData['price'])) {
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

        GroupClass::where('teacher_id', $teacherId)->delete();

        foreach ($data['groups'] as $groupData) {
            if (!empty($groupData['title'])) {
                $maxStudents = isset($groupData['max_students']) ? (int) $groupData['max_students'] : 1;
                if ($maxStudents < 1) {
                    $maxStudents = 1;
                } elseif ($maxStudents > 100) {
                    $maxStudents = 100;
                }

                // Clean and validate description
                $description = null;
                if (isset($groupData['description']) && !empty(trim($groupData['description']))) {
                    $description = trim($groupData['description']);
                    // Limit description to 500 characters
                    if (strlen($description) > 500) {
                        $description = substr($description, 0, 500);
                    }
                    // Optional: Strip HTML tags for security
                    $description = strip_tags($description);
                }

                $group = GroupClass::create([
                    'teacher_id' => $teacherId,
                    'title' => $groupData['title'],
                    'description' => $description, // Add description field
                    'duration_per_class' => $groupData['duration_per_class'],
                    'lessons_per_week' => $groupData['lessons_per_week'],
                    'max_students' => $maxStudents, // Use validated value
                    'price_per_student' => $groupData['price_per_student'],
                    'is_active' => isset($groupData['is_active']) ? 1 : 0,
                ]);

                if (isset($groupData['days']) && is_array($groupData['days'])) {
                    foreach ($groupData['days'] as $day) {
                        $group->days()->create(['day' => $day]);
                    }
                }
            }
        }
    }
}
