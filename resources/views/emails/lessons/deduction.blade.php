@component('mail::message')
    # 📢 Lesson Deduction Request

    Hello **{{ $package->teacher->name }}**,

    Student **{{ $student->name }}** has requested to deduct a lesson from their package.

    ---

    **Package:** {{ $package->package_summary }}
    **Total Lessons:** {{ $package->total_lessons_purchased }}
    **Taken:** {{ $package->lessons_taken }}
    **Remaining:** {{ $package->lessons_remaining }}
    **Price per Lesson:** ${{ number_format($package->price_per_lesson, 2) }}
    **Purchase Date:** {{ \Carbon\Carbon::parse($package->purchase_date)->format('M d, Y') }}

    ---

    @component('mail::button', ['url' => url('/teacher/zoom-meetings')])
        Review Request
    @endcomponent

    Thanks,
    {{ config('app.name') }}
@endcomponent
