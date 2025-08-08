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
            // Individual lesson pricing
            'duration_30' => 'nullable|numeric|min:0',
            'duration_60' => 'nullable|numeric|min:0',
            'duration_90' => 'nullable|numeric|min:0',
            'duration_120' => 'nullable|numeric|min:0',

            // Lesson packages validation
            'packages' => 'nullable|array',
            'packages.*.name' => 'required_with:packages.*.number_of_lessons|string|max:255',
            'packages.*.number_of_lessons' => 'required_with:packages.*.name|integer|min:1|max:100',
            'packages.*.duration_per_lesson' => 'required_with:packages.*.name|integer|in:30,60,90,120',
            'packages.*.price' => 'required_with:packages.*.name|numeric|min:0',
            'packages.*.is_active' => 'nullable|boolean',

            // Group classes validation
            'groups' => 'nullable|array',
            'groups.*.title' => 'required_with:groups|string|max:255',
            'groups.*.duration_per_class' => 'required_with:groups.*.title|integer|in:60,90',
            'groups.*.lessons_per_week' => 'required_with:groups.*.title|integer|min:1|max:5',
            'groups.*.max_students' => 'required_with:groups.*|integer|min:1|max:100',
            'groups.*.price_per_student' => 'required_with:groups.*.title|numeric|min:0',
            'groups.*.is_active' => 'nullable|boolean',
            'groups.*.days' => 'required_with:groups.*.title|array|min:1',
            'groups.*.days.*' => 'string|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
        ];
    }

    public function messages()
    {
        return [
            // Individual pricing messages
            'duration_30.numeric' => 'Duration 30 min price must be a valid number.',
            'duration_60.numeric' => 'Duration 60 min price must be a valid number.',
            'duration_90.numeric' => 'Duration 90 min price must be a valid number.',
            'duration_120.numeric' => 'Duration 120 min price must be a valid number.',

            // Package messages
            'packages.*.name.required_with' => 'Package name is required.',
            'packages.*.number_of_lessons.required_with' => 'Number of lessons is required for each package.',
            'packages.*.duration_per_lesson.required_with' => 'Duration per lesson is required for each package.',
            'packages.*.price.required_with' => 'Price is required for each package.',

            // Group messages
            'groups.*.title.required_with' => 'Group title is required.',
            'groups.*.duration_per_class.required_with' => 'Class duration is required.',
            'groups.*.lessons_per_week.required_with' => 'Lessons per week is required.',
            'groups.*.max_students.required_with' => 'Maximum students is required.',
            'groups.*.max_students.integer' => 'Maximum students must be a valid number.',
            'groups.*.max_students.min' => 'Maximum students must be at least 1.',
            'groups.*.max_students.max' => 'Maximum students cannot exceed 100.',
            'groups.*.price_per_student.required_with' => 'Price per student is required.',
            'groups.*.days.required_with' => 'At least one day must be selected for each group.',
            'groups.*.days.min' => 'At least one day must be selected for each group.',
        ];
    }

    protected function prepareForValidation()
    {
        // Convert checkbox values to boolean and clean up data
        if ($this->has('packages')) {
            $packages = $this->input('packages');
            foreach ($packages as $key => $package) {
                $packages[$key]['is_active'] = isset($package['is_active']) ? true : false;
            }
            $this->merge(['packages' => $packages]);
        }

        if ($this->has('groups')) {
            $groups = $this->input('groups');
            foreach ($groups as $key => $group) {
                $groups[$key]['is_active'] = isset($group['is_active']) ? true : false;
                
                // Ensure max_students is properly formatted as integer
                if (isset($group['max_students'])) {
                    $groups[$key]['max_students'] = (int) $group['max_students'];
                }
            }
            $this->merge(['groups' => $groups]);
        }
    }
}