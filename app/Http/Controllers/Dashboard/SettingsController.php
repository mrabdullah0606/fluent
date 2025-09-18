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
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $settings = TeacherSetting::where('teacher_id', $teacher->id)
            ->pluck('value', 'key')
            ->toArray();

        $packages = LessonPackage::where('teacher_id', $teacher->id)
            ->orderBy('package_number')
            ->get()
            ->keyBy('package_number')
            ->toArray();

        // $groups = GroupClass::where('teacher_id', $teacher->id)
        //     ->with('days')
        //     ->get()
        //     ->map(function ($group) {
        //         $features = $group->features;
        //         if (is_string($features)) {
        //             try {
        //                 $decoded = json_decode($features, true);
        //                 $features = is_array($decoded) ? $decoded : array_map('trim', explode(',', $features));
        //             } catch (\Exception $e) {
        //                 $features = array_map('trim', explode(',', $features));
        //             }
        //         }
        //         if (!is_array($features)) {
        //             $features = [];
        //         }

        //         $days = [];
        //         $times = [];
        //         foreach ($group->days as $d) {
        //             $days[] = $d->day instanceof \Carbon\Carbon
        //                 ? $d->day->format('Y-m-d')
        //                 : (string) $d->day;

        //             $times[] = $d->time ?? '';
        //         }

        //         return [
        //             'title'             => $group->title,
        //             'description'       => $group->description,
        //             'duration_per_class' => $group->duration_per_class,
        //             'lessons_per_week'  => $group->lessons_per_week,
        //             'max_students'      => $group->max_students,
        //             'features'          => $features,
        //             'price_per_student' => $group->price_per_student,
        //             'is_active'         => $group->is_active,
        //             'days'              => $days,
        //             'times'             => $times,
        //         ];
        //     })
        //     ->values() // Re-index the array
        //     ->toArray();
        return view('teacher.content.profile.settings', compact('settings', 'packages',/*  'groups' */));
    }

    public function update(SettingsRequest $request)
    {
        Log::info('🚀 UPDATE METHOD CALLED');

        DB::beginTransaction();
        try {
            $teacher = Auth::user();
            $validatedData = $request->validated();

            Log::info('✅ VALIDATION PASSED');
            Log::info('Validated data structure:', [
                'has_groups' => isset($validatedData['groups']),
                'groups_count' => isset($validatedData['groups']) ? count($validatedData['groups']) : 0,
                'other_keys' => array_keys($validatedData)
            ]);

            $this->updateIndividualPricing($teacher->id, $validatedData);
            Log::info('✅ Individual pricing updated');

            $this->updateLessonPackages($teacher->id, $validatedData);
            Log::info('✅ Lesson packages updated');

            // Check if groups data exists before processing
            // if (isset($validatedData['groups']) && is_array($validatedData['groups'])) {
            //     Log::info('✅ Processing groups data');
            //     $this->updateGroupClasses($teacher->id, $validatedData);
            //     Log::info('✅ Group classes updated');
            // } else {
            //     Log::warning('⚠️ No groups data found in request, skipping group update');
            // }

            DB::commit();
            Log::info('✅ Transaction committed successfully');

            return redirect()->back()->with('success', 'Settings updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Error in update method:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    private function updateIndividualPricing($teacherId, array $data)
    {
        $durations = [60];

        foreach ($durations as $duration) {
            $key = "duration_{$duration}";
            if (isset($data[$key]) && $data[$key] !== null && $data[$key] !== '') {
                TeacherSetting::updateOrCreate(
                    ['teacher_id' => $teacherId, 'key' => $key],
                    ['value' => $data[$key]]
                );
            }
        }
    }

    private function updateLessonPackages($teacherId, array $data)
    {
        if (!isset($data['packages']) || !is_array($data['packages'])) {
            return;
        }

        // Delete existing packages
        LessonPackage::where('teacher_id', $teacherId)->delete();

        foreach ($data['packages'] as $packageNumber => $packageData) {
            // Only create package if essential fields are present
            if (
                isset($packageData['number_of_lessons']) &&
                !empty($packageData['number_of_lessons']) &&
                isset($packageData['price']) &&
                !empty($packageData['price'])
            ) {
                LessonPackage::create([
                    'teacher_id' => $teacherId,
                    'package_number' => $packageNumber,
                    'name' => $packageData['name'] ?? "Package {$packageNumber}",
                    'number_of_lessons' => (int) $packageData['number_of_lessons'],
                    'duration_per_lesson' => (int) ($packageData['duration_per_lesson'] ?? 60),
                    'price' => (float) $packageData['price'],
                    'is_active' => isset($packageData['is_active']) ? (bool) $packageData['is_active'] : false,
                ]);
            }
        }
    }

    // private function updateGroupClasses($teacherId, array $data)
    // {
    //     Log::info('=== GROUP CLASSES UPDATE START ===');

    //     if (!isset($data['groups']) || !is_array($data['groups'])) {
    //         Log::error('❌ No groups data found');
    //         return;
    //     }

    //     Log::info('Groups data received:', $data['groups']);

    //     // Extract submitted group IDs
    //     $submittedGroupIds = collect($data['groups'])
    //         ->pluck('id')
    //         ->filter() // Remove null or empty
    //         ->map(fn($id) => (int) $id)
    //         ->toArray();

    //     $existingGroups = GroupClass::where('teacher_id', $teacherId)->get();

    //     // Delete groups removed from form submission
    //     $existingGroups->filter(fn($group) => !in_array($group->id, $submittedGroupIds))
    //         ->each(function ($group) {
    //             $group->days()->delete();
    //             $group->delete();
    //             Log::info("Deleted group ID {$group->id} - removed in form submission.");
    //         });

    //     $processedGroups = 0;
    //     $processedGroupIds = [];

    //     foreach ($data['groups'] as $index => $groupData) {
    //         if (empty($groupData['title']) || trim($groupData['title']) === '') {
    //             Log::warning("Skipping group {$index} due to empty title.");
    //             continue;
    //         }

    //         try {
    //             $groupCreateData = [
    //                 'teacher_id'         => $teacherId,
    //                 'title'              => trim($groupData['title']),
    //                 'description'        => !empty($groupData['description'])
    //                     ? strip_tags(substr(trim($groupData['description']), 0, 500))
    //                     : null,
    //                 'duration_per_class'  => (int) ($groupData['duration_per_class'] ?? 60),
    //                 'lessons_per_week'    => (int) ($groupData['lessons_per_week'] ?? 1),
    //                 'max_students'       => max(1, min((int) ($groupData['max_students'] ?? 1), 100)),
    //                 'price_per_student'  => (float) ($groupData['price_per_student'] ?? 0),
    //                 'is_active'          => isset($groupData['is_active']) ? (bool) $groupData['is_active'] : false,
    //             ];

    //             // Handle features
    //             $features = [];
    //             if (!empty($groupData['features'])) {
    //                 if (is_array($groupData['features'])) {
    //                     $features = array_filter($groupData['features']);
    //                 } else {
    //                     $features = array_filter(array_map('trim', explode(',', (string) $groupData['features'])));
    //                 }
    //             }
    //             $groupCreateData['features'] = json_encode($features);

    //             if (!empty($groupData['id'])) {
    //                 $group = GroupClass::where('id', $groupData['id'])->where('teacher_id', $teacherId)->first();
    //                 if ($group) {
    //                     $group->update($groupCreateData);
    //                     Log::info("Updated group ID {$group->id}");
    //                     $group->days()->delete();
    //                 } else {
    //                     $group = GroupClass::create($groupCreateData);
    //                     Log::info("Created new group because ID {$groupData['id']} not found: {$group->id}");
    //                 }
    //             } else {
    //                 $group = GroupClass::create($groupCreateData);
    //                 Log::info("Created new group ID {$group->id}");
    //             }

    //             $processedGroupIds[] = $group->id;
    //             $processedGroups++;

    //             if (isset($groupData['days']) && is_array($groupData['days'])) {
    //                 foreach ($groupData['days'] as $dayIndex => $day) {
    //                     if (!empty($day) && trim($day) !== '') {
    //                         $time = isset($groupData['times'][$dayIndex]) && !empty($groupData['times'][$dayIndex])
    //                             ? $groupData['times'][$dayIndex]
    //                             : null;
    //                         $group->days()->create(['day' => $day, 'time' => $time]);
    //                         Log::info("Created schedule day {$day} time {$time} for group {$group->id}");
    //                     }
    //                 }
    //             }
    //         } catch (\Exception $e) {
    //             Log::error("Error processing group {$index}: {$e->getMessage()}", $e->getTrace());
    //             throw $e;
    //         }
    //     }

    //     Log::info("=== GROUP CLASSES UPDATE SUMMARY === Groups processed: {$processedGroups}");
    //     Log::info("Processed group IDs: ", $processedGroupIds);

    //     $finalCount = GroupClass::where('teacher_id', $teacherId)->count();
    //     Log::info("Final groups count in database: {$finalCount}");
    // }
}
