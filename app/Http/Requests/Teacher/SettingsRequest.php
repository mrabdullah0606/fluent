<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules()
    {
        return [
            // Individual lesson pricing
            'duration_30' => 'nullable|numeric|min:0|max:999.99',
            'duration_60' => 'nullable|numeric|min:0|max:999.99',
            'duration_90' => 'nullable|numeric|min:0|max:999.99',
            'duration_120' => 'nullable|numeric|min:0|max:999.99',

            // Lesson packages
            'packages' => 'nullable|array',
            'packages.*' => 'array',
            'packages.*.name' => 'required_with:packages.*|string|max:255',
            'packages.*.number_of_lessons' => 'required_with:packages.*|integer|min:1|max:100',
            'packages.*.duration_per_lesson' => 'required_with:packages.*|in:30,60,90,120',
            'packages.*.price' => 'required_with:packages.*|numeric|min:0|max:9999.99',
            'packages.*.is_active' => 'nullable|boolean',

            // Group classes
            'groups' => 'nullable|array',
            'groups.*' => 'array',
            'groups.*.title' => 'required_with:groups.*|string|max:255',
            'groups.*.duration_per_class' => 'required_with:groups.*|in:60,90',
            'groups.*.lessons_per_week' => 'required_with:groups.*|integer|min:1|max:5',
            'groups.*.max_students' => 'required_with:groups.*|in:20,30',
            'groups.*.price_per_student' => 'required_with:groups.*|numeric|min:0|max:999.99',
            'groups.*.days' => 'required_with:groups.*|array|min:1',
            'groups.*.days.*' => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'groups.*.is_active' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'packages.*.number_of_lessons.required_with' => 'Number of lessons is required for each package.',
            'packages.*.duration_per_lesson.required_with' => 'Duration per lesson is required for each package.',
            'packages.*.price.required_with' => 'Price is required for each package.',

            'groups.*.title.required_with' => 'Group title is required.',
            'groups.*.duration_per_class.required_with' => 'Class duration is required.',
            'groups.*.lessons_per_week.required_with' => 'Lessons per week is required.',
            'groups.*.max_students.required_with' => 'Maximum students is required.',
            'groups.*.price_per_student.required_with' => 'Price per student is required.',
            'groups.*.days.required_with' => 'At least one day must be selected for each group.',
            'groups.*.days.min' => 'At least one day must be selected for each group.',
        ];
    }

    protected function prepareForValidation()
    {
        // Convert checkbox values to boolean
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
            }
            $this->merge(['groups' => $groups]);
        }
    }
}
