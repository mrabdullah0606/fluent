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

        'groups' => 'array',
        'groups.*.title' => 'required|string|max:255',
        'groups.*.description' => 'nullable|string|max:500',
        'groups.*.duration_per_class' => 'required|integer|in:60,90',
        'groups.*.lessons_per_week' => 'required|integer|min:1|max:5',
        'groups.*.max_students' => 'required|integer|min:1|max:100',
        'groups.*.price_per_student' => 'required|numeric|min:0',
        'groups.*.features' => 'nullable|string',
        'groups.*.days' => 'array',
        'groups.*.days.*' => 'nullable|string',
        'groups.*.times' => 'array',
        'groups.*.times.*' => 'nullable|string',
        'groups.*.is_active' => 'nullable|boolean',
    ];
}


    public function messages()
    {
        return [
            // Individual pricing messages
            'duration_30.numeric'  => 'Duration 30 min price must be a valid number.',
            'duration_60.numeric'  => 'Duration 60 min price must be a valid number.',
            'duration_90.numeric'  => 'Duration 90 min price must be a valid number.',
            'duration_120.numeric' => 'Duration 120 min price must be a valid number.',

            // Package messages
            'packages.*.name.required_with' => 'Package name is required.',
            'packages.*.number_of_lessons.required_with' => 'Number of lessons is required for each package.',
            'packages.*.duration_per_lesson.required_with' => 'Duration per lesson is required for each package.',
            'packages.*.price.required_with' => 'Price is required for each package.',

            // Group messages
            'groups.*.title.required_with' => 'Group title is required.',
            'groups.*.description.max' => 'Description must not exceed 500 characters.',
            'groups.*.duration_per_class.required_with' => 'Class duration is required.',
            'groups.*.lessons_per_week.required_with' => 'Lessons per week is required.',
            'groups.*.max_students.required_with' => 'Maximum students is required.',
            'groups.*.max_students.integer' => 'Maximum students must be a valid number.',
            'groups.*.max_students.min' => 'Maximum students must be at least 1.',
            'groups.*.max_students.max' => 'Maximum students cannot exceed 100.',
            'groups.*.price_per_student.required_with' => 'Price per student is required.',
            'groups.*.days.required_with' => 'At least one date must be selected for each group.',
            'groups.*.days.min' => 'At least one date must be selected for each group.',
            'groups.*.times.required_with' => 'Time is required for each selected day.',
        ];
    }

    protected function prepareForValidation()
    {
        // Normalize packages
        if ($this->has('packages')) {
            $packages = $this->input('packages');
            foreach ($packages as $key => $package) {
                $packages[$key]['is_active'] = isset($package['is_active']);
            }
            $this->merge(['packages' => $packages]);
        }

        // Normalize groups
        if ($this->has('groups')) {
            $groups = $this->input('groups');
            foreach ($groups as $key => $group) {
                $groups[$key]['is_active'] = isset($group['is_active']);

                // Ensure max_students is an integer
                if (isset($group['max_students'])) {
                    $groups[$key]['max_students'] = (int) $group['max_students'];
                }

                // Convert features string -> array
                if (!empty($group['features']) && is_string($group['features'])) {
                    $groups[$key]['features'] = array_map('trim', explode(',', $group['features']));
                }
            }
            $this->merge(['groups' => $groups]);
        }
    }
}
