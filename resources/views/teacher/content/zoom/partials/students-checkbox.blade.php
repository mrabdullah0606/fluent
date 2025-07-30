@if ($students->isNotEmpty())
    <label class="mb-2">Select Attendees</label>
    @foreach ($students as $student)
        <div class="d-flex align-items-center mb-2">
            {{-- Using email as value for checkbox --}}
            <input type="checkbox" name="attendees[]" value="{{ $student->email }}" id="attendee_{{ $student->id }}"
                class="">
            <label class="form-check-label" for="attendee_{{ $student->id }}">
                {{ $student->name }} ({{ $student->email }})
            </label>
        </div>
    @endforeach
@else
    <p class="text-muted">No attendees found for this course.</p>
@endif
