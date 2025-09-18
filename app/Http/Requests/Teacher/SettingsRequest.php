<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'duration_60' => 'nullable|numeric|min:0',

            'packages' => 'array',
            'packages.*.number_of_lessons' => 'required|integer|min:1',
            'packages.*.duration_per_lesson' => 'required|integer|in:60,90',
            'packages.*.price' => 'required|numeric|min:0',
            'packages.*.name' => 'required|string|max:255',
            'packages.*.is_active' => 'nullable|boolean',

            // 'groups' => 'array',
            // 'groups.*.title' => 'required|string|max:255',
            // 'groups.*.description' => 'nullable|string|max:500',
            // 'groups.*.duration_per_class' => 'required|integer|in:60,90',
            // 'groups.*.lessons_per_week' => 'required|integer|min:1|max:5',
            // 'groups.*.max_students' => 'required|integer|min:1|max:100',
            // 'groups.*.price_per_student' => 'required|numeric|min:0',
            // 'groups.*.features' => 'nullable|string',
            // 'groups.*.days' => 'required|array|min:1',
            // 'groups.*.days.*' => 'required|date',
            // 'groups.*.times' => 'required|array',
            // 'groups.*.times.*' => 'nullable|date_format:H:i',
            // 'groups.*.is_active' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            // Individual pricing messages
            'duration_60.numeric'  => 'Duration 60 min price must be a valid number.',

            // Package messages
            'packages.*.name.required' => 'Package name is required.',
            'packages.*.number_of_lessons.required' => 'Number of lessons is required for each package.',
            'packages.*.duration_per_lesson.required' => 'Duration per lesson is required for each package.',
            'packages.*.price.required' => 'Price is required for each package.',

            // Group messages
            // 'groups.*.title.required' => 'Group title is required.',
            // 'groups.*.description.max' => 'Description must not exceed 500 characters.',
            // 'groups.*.duration_per_class.required' => 'Class duration is required.',
            // 'groups.*.lessons_per_week.required' => 'Lessons per week is required.',
            // 'groups.*.max_students.required' => 'Maximum students is required.',
            // 'groups.*.max_students.integer' => 'Maximum students must be a valid number.',
            // 'groups.*.max_students.min' => 'Maximum students must be at least 1.',
            // 'groups.*.max_students.max' => 'Maximum students cannot exceed 100.',
            // 'groups.*.price_per_student.required' => 'Price per student is required.',
            // 'groups.*.days.required' => 'At least one date must be selected for each group.',
            // 'groups.*.days.min' => 'At least one date must be selected for each group.',
            // 'groups.*.times.required' => 'Time is required for each selected day.',
        ];
    }

    protected function prepareForValidation()
    {
        // Get all input data
        $data = $this->all();

        // Normalize packages
        if ($this->has('packages')) {
            $packages = $this->input('packages');
            foreach ($packages as $key => $package) {
                // Convert is_active checkbox to boolean
                $packages[$key]['is_active'] = isset($package['is_active']) && $package['is_active'] == '1';

                // Ensure numeric fields are properly formatted
                if (isset($package['number_of_lessons'])) {
                    $packages[$key]['number_of_lessons'] = (int) $package['number_of_lessons'];
                }
                if (isset($package['duration_per_lesson'])) {
                    $packages[$key]['duration_per_lesson'] = (int) $package['duration_per_lesson'];
                }
                if (isset($package['price'])) {
                    $packages[$key]['price'] = (float) $package['price'];
                }
            }
            $data['packages'] = $packages;
        }

        // Normalize groups - this is the crucial fix
        // if ($this->has('groups')) {
        //     $groups = $this->input('groups');
        //     $normalizedGroups = [];

        //     foreach ($groups as $key => $group) {
        //         // Skip empty groups
        //         if (empty($group['title'])) {
        //             continue;
        //         }

        //         // Convert is_active checkbox to boolean
        //         $normalizedGroups[$key]['title'] = trim($group['title']);
        //         $normalizedGroups[$key]['description'] = isset($group['description']) ? trim($group['description']) : null;
        //         $normalizedGroups[$key]['duration_per_class'] = (int) ($group['duration_per_class'] ?? 60);
        //         $normalizedGroups[$key]['lessons_per_week'] = (int) ($group['lessons_per_week'] ?? 1);
        //         $normalizedGroups[$key]['max_students'] = (int) ($group['max_students'] ?? 1);
        //         $normalizedGroups[$key]['price_per_student'] = (float) ($group['price_per_student'] ?? 0);
        //         $normalizedGroups[$key]['is_active'] = isset($group['is_active']) && $group['is_active'] == '1';

        //         // Handle features - convert string to comma-separated or keep as string
        //         if (!empty($group['features'])) {
        //             $normalizedGroups[$key]['features'] = is_string($group['features']) ? $group['features'] : '';
        //         } else {
        //             $normalizedGroups[$key]['features'] = '';
        //         }

        //         // Handle days and times arrays - ensure they are properly formatted
        //         $normalizedGroups[$key]['days'] = [];
        //         $normalizedGroups[$key]['times'] = [];

        //         if (isset($group['days']) && is_array($group['days'])) {
        //             foreach ($group['days'] as $index => $day) {
        //                 if (!empty($day)) {
        //                     $normalizedGroups[$key]['days'][] = $day;
        //                     $normalizedGroups[$key]['times'][] = isset($group['times'][$index]) ? $group['times'][$index] : null;
        //                 }
        //             }
        //         }
        //     }

        //     $data['groups'] = $normalizedGroups;
        // }

        // Replace the request data
        $this->replace($data);
    }
}
