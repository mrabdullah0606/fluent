<div class="settings-card" id="groupClassesContainer">
    <h5 class="fw-bold text-dark mb-3">
        <i class="bi bi-people-fill fs-3 text-warning me-1"></i> Group Classes
    </h5>
    <p class="text-muted">Set up and manage your group classes.</p>

    @if (!empty($groups) && count($groups) > 0)
        @foreach ($groups as $index => $group)
            {{-- <div class="group-container">
                <input type="text" name="groups[{{ $index }}][title]"
                    class="form-control fw-bold editable-heading mb-3"
                    value="{{ old('groups.' . $index . '.title', $group['title'] ?? 'French for Beginners (A1)') }}"
                    placeholder="Group class title" required>

                <!-- Description Textarea -->
                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">Description</label>
                    <textarea class="form-control" name="groups[{{ $index }}][description]" rows="3"
                        placeholder="Describe your group class (e.g., curriculum, teaching method, target audience...)"
                        style="resize: vertical;">{{ old('groups.' . $index . '.description', $group['description'] ?? '') }}</textarea>
                    <div class="form-text text-muted small">
                        Optional: Help students understand what this class offers
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-6 col-md-3">
                        <label class="form-label small" for="group_{{ $index }}_duration">Duration</label>
                        <select class="form-select" name="groups[{{ $index }}][duration_per_class]"
                            id="group_{{ $index }}_duration" required>
                            <option value="60"
                                {{ old('groups.' . $index . '.duration_per_class', $group['duration_per_class'] ?? 60) == 60 ? 'selected' : '' }}>
                                60 min</option>
                            <option value="90"
                                {{ old('groups.' . $index . '.duration_per_class', $group['duration_per_class'] ?? 60) == 90 ? 'selected' : '' }}>
                                90 min</option>
                        </select>
                    </div>

                    <div class="col-6 col-md-3">
                        <label class="form-label small" for="group_{{ $index }}_lessons">Lessons/week</label>
                        <select class="form-select" name="groups[{{ $index }}][lessons_per_week]"
                            id="group_{{ $index }}_lessons" required>
                            @for ($j = 1; $j <= 5; $j++)
                                <option value="{{ $j }}"
                                    {{ old('groups.' . $index . '.lessons_per_week', $group['lessons_per_week'] ?? 1) == $j ? 'selected' : '' }}>
                                    {{ $j }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-6 col-md-3">
                        <label class="form-label small" for="group_{{ $index }}_max">Max Students</label>
                        <input type="number" class="form-control" name="groups[{{ $index }}][max_students]"
                            id="group_{{ $index }}_max" min="1" max="100"
                            placeholder="Enter max students"
                            value="{{ old('groups.' . $index . '.max_students', $group['max_students'] ?? '') }}"
                            required>
                    </div>

                    <div class="col-6 col-md-3">
                        <label class="form-label small" for="group_{{ $index }}_price">Price per Student
                            ($)</label>
                        <input type="number" class="form-control"
                            name="groups[{{ $index }}][price_per_student]" id="group_{{ $index }}_price"
                            step="0.01" min="0" placeholder="0.00"
                            value="{{ old('groups.' . $index . '.price_per_student', $group['price_per_student'] ?? '') }}"
                            required>
                    </div>

                    <div class="col-12">
                        <label class="form-label small" for="group_{{ $index }}_features">
                            <strong>Features</strong>
                        </label>
                        <input type="text" class="form-control" name="groups[{{ $index }}][features]"
                            id="group_{{ $index }}_features"
                            placeholder="Interactive role-plays, Cultural insights"
                            value="{{ old('groups.' . $index . '.features', is_array($group['features'] ?? []) ? implode(', ', $group['features']) : $group['features'] ?? '') }}">
                        <div class="form-text text-muted small">
                            Separate multiple features with commas
                        </div>
                    </div>
                </div>

                <!-- Days and Times Section -->
                <div class="mt-3">
                    <label class="form-label small fw-bold text-dark">Class Schedule</label>
                    <div class="schedule-container">
                        @if (!empty($group['days']) && count($group['days']) > 0)
                            @foreach ($group['days'] as $i => $day)
                                <div class="schedule-item row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label small text-muted">Date</label>
                                        <input type="date" name="groups[{{ $index }}][days][]"
                                            class="form-control"
                                            value="{{ old('groups.' . $index . '.days.' . $i, $day) }}" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small text-muted">Time</label>
                                        <div class="input-group">
                                            <input type="time" name="groups[{{ $index }}][times][]"
                                                class="form-control"
                                                value="{{ old('groups.' . $index . '.times.' . $i, $group['times'][$i] ?? '') }}"
                                                required>
                                            @if ($i > 0)
                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm remove-schedule">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="schedule-item row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small text-muted">Date</label>
                                    <input type="date" name="groups[{{ $index }}][days][]"
                                        class="form-control" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted">Time</label>
                                    <input type="time" name="groups[{{ $index }}][times][]"
                                        class="form-control" required>
                                </div>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-warning add-schedule"
                        data-group-index="{{ $index }}">
                        <i class="bi bi-plus"></i> Add Another Day
                    </button>
                </div>

                <!-- Toggle Switch -->
                <div class="form-check form-switch position-absolute top-0 end-0 mt-2 me-3">
                    <input class="form-check-input bg-warning border" type="checkbox"
                        name="groups[{{ $index }}][is_active]" id="group_{{ $index }}_active"
                        value="1"
                        {{ old('groups.' . $index . '.is_active', $group['is_active'] ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="group_{{ $index }}_active">Active</label>
                </div>
            </div> --}}
            <div class="group-container">
                {{-- Add hidden field for existing group ID --}}
                <input type="hidden" name="groups[{{ $index }}][id]" value="{{ $group['id'] ?? '' }}">

                <input type="text" name="groups[{{ $index }}][title]"
                    class="form-control fw-bold editable-heading mb-3"
                    value="{{ old('groups.' . $index . '.title', $group['title'] ?? 'French for Beginners (A1)') }}"
                    placeholder="Group class title" required>

                <!-- Description Textarea -->
                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">Description</label>
                    <textarea class="form-control" name="groups[{{ $index }}][description]" rows="3"
                        placeholder="Describe your group class (e.g., curriculum, teaching method, target audience...)"
                        style="resize: vertical;">{{ old('groups.' . $index . '.description', $group['description'] ?? '') }}</textarea>
                    <div class="form-text text-muted small">
                        Optional: Help students understand what this class offers
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-6 col-md-3">
                        <label class="form-label small" for="group_{{ $index }}_duration">Duration</label>
                        <select class="form-select" name="groups[{{ $index }}][duration_per_class]"
                            id="group_{{ $index }}_duration" required>
                            <option value="60"
                                {{ old('groups.' . $index . '.duration_per_class', $group['duration_per_class'] ?? 60) == 60 ? 'selected' : '' }}>
                                60 min</option>
                            <option value="90"
                                {{ old('groups.' . $index . '.duration_per_class', $group['duration_per_class'] ?? 60) == 90 ? 'selected' : '' }}>
                                90 min</option>
                        </select>
                    </div>

                    <div class="col-6 col-md-3">
                        <label class="form-label small" for="group_{{ $index }}_lessons">Lessons/week</label>
                        <select class="form-select" name="groups[{{ $index }}][lessons_per_week]"
                            id="group_{{ $index }}_lessons" required>
                            @for ($j = 1; $j <= 5; $j++)
                                <option value="{{ $j }}"
                                    {{ old('groups.' . $index . '.lessons_per_week', $group['lessons_per_week'] ?? 1) == $j ? 'selected' : '' }}>
                                    {{ $j }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-6 col-md-3">
                        <label class="form-label small" for="group_{{ $index }}_max">Max Students</label>
                        <input type="number" class="form-control" name="groups[{{ $index }}][max_students]"
                            id="group_{{ $index }}_max" min="1" max="100"
                            placeholder="Enter max students"
                            value="{{ old('groups.' . $index . '.max_students', $group['max_students'] ?? '') }}"
                            required>
                    </div>

                    <div class="col-6 col-md-3">
                        <label class="form-label small" for="group_{{ $index }}_price">Price per Student
                            ($)</label>
                        <input type="number" class="form-control"
                            name="groups[{{ $index }}][price_per_student]" id="group_{{ $index }}_price"
                            step="0.01" min="0" placeholder="0.00"
                            value="{{ old('groups.' . $index . '.price_per_student', $group['price_per_student'] ?? '') }}"
                            required>
                    </div>

                    <div class="col-12">
                        <label class="form-label small" for="group_{{ $index }}_features">
                            <strong>Features</strong>
                        </label>
                        <input type="text" class="form-control" name="groups[{{ $index }}][features]"
                            id="group_{{ $index }}_features"
                            placeholder="Interactive role-plays, Cultural insights"
                            value="{{ old('groups.' . $index . '.features', is_array($group['features'] ?? []) ? implode(', ', $group['features']) : $group['features'] ?? '') }}">
                        <div class="form-text text-muted small">
                            Separate multiple features with commas
                        </div>
                    </div>
                </div>

                <!-- Days and Times Section -->
                <div class="mt-3">
                    <label class="form-label small fw-bold text-dark">Class Schedule</label>
                    <div class="schedule-container">
                        @if (!empty($group['days']) && count($group['days']) > 0)
                            @foreach ($group['days'] as $i => $day)
                                <div class="schedule-item row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label small text-muted">Date</label>
                                        <input type="date" name="groups[{{ $index }}][days][]"
                                            class="form-control"
                                            value="{{ old('groups.' . $index . '.days.' . $i, $day) }}" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small text-muted">Time</label>
                                        <div class="input-group">
                                            <input type="time" name="groups[{{ $index }}][times][]"
                                                class="form-control"
                                                value="{{ old('groups.' . $index . '.times.' . $i, $group['times'][$i] ?? '') }}"
                                                required>
                                            @if ($i > 0)
                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm remove-schedule">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="schedule-item row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small text-muted">Date</label>
                                    <input type="date" name="groups[{{ $index }}][days][]"
                                        class="form-control" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted">Time</label>
                                    <input type="time" name="groups[{{ $index }}][times][]"
                                        class="form-control" required>
                                </div>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-warning add-schedule"
                        data-group-index="{{ $index }}">
                        <i class="bi bi-plus"></i> Add Another Day
                    </button>
                </div>

                <!-- Toggle Switch -->
                <div class="form-check form-switch position-absolute top-0 end-0 mt-2 me-3">
                    <input class="form-check-input bg-warning border" type="checkbox"
                        name="groups[{{ $index }}][is_active]" id="group_{{ $index }}_active"
                        value="1"
                        {{ old('groups.' . $index . '.is_active', $group['is_active'] ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="group_{{ $index }}_active">Active</label>
                </div>
            </div>
        @endforeach
    @else
        <div class="group-container">
            <input type="text" name="groups[0][title]" class="form-control fw-bold editable-heading mb-3"
                value="{{ old('groups.0.title', 'French for Beginners (A1)') }}" placeholder="Group class title"
                required>

            <!-- Description Textarea -->
            <div class="mb-3">
                <label class="form-label small fw-bold text-dark">Description</label>
                <textarea class="form-control" name="groups[0][description]" rows="3"
                    placeholder="Describe your group class (e.g., curriculum, teaching method, target audience...)"
                    style="resize: vertical;">{{ old('groups.0.description', '') }}</textarea>
                <div class="form-text text-muted small">
                    Optional: Help students understand what this class offers
                </div>
            </div>

            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <label class="form-label small">Duration</label>
                    <select class="form-select" name="groups[0][duration_per_class]" required>
                        <option value="60" {{ old('groups.0.duration_per_class', 60) == 60 ? 'selected' : '' }}>60
                            min</option>
                        <option value="90" {{ old('groups.0.duration_per_class') == 90 ? 'selected' : '' }}>90 min
                        </option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small">Lessons/week</label>
                    <select class="form-select" name="groups[0][lessons_per_week]" required>
                        @for ($j = 1; $j <= 5; $j++)
                            <option value="{{ $j }}"
                                {{ old('groups.0.lessons_per_week', 1) == $j ? 'selected' : '' }}>{{ $j }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small">Max Students</label>
                    <input type="number" class="form-control" name="groups[0][max_students]" min="1"
                        max="100" placeholder="Enter max students"
                        value="{{ old('groups.0.max_students', '') }}" required>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small">Price per Student ($)</label>
                    <input type="number" class="form-control" name="groups[0][price_per_student]" step="0.01"
                        placeholder="0.00" value="{{ old('groups.0.price_per_student', '') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label small">
                        <strong>Features</strong>
                    </label>
                    <input type="text" class="form-control" name="groups[0][features]"
                        placeholder="Interactive role-plays, Cultural insights"
                        value="{{ old('groups.0.features', '') }}">
                    <div class="form-text text-muted small">
                        Separate multiple features with commas
                    </div>
                </div>
            </div>

            <!-- Days and Times Section for Empty State -->
            <div class="mt-3">
                <label class="form-label small fw-bold text-dark">Class Schedule</label>
                <div class="schedule-container">
                    <div class="schedule-item row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small text-muted">Date</label>
                            <input type="date" name="groups[0][days][]" class="form-control"
                                value="{{ old('groups.0.days.0', '') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted">Time</label>
                            <input type="time" name="groups[0][times][]" class="form-control"
                                value="{{ old('groups.0.times.0', '') }}" required>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-warning add-schedule" data-group-index="0">
                    <i class="bi bi-plus"></i> Add Another Day
                </button>
            </div>

            <!-- Toggle Switch -->
            <div class="form-check form-switch position-absolute top-0 end-0 mt-2 me-3">
                <input class="form-check-input bg-warning border" type="checkbox" name="groups[0][is_active]"
                    value="1" {{ old('groups.0.is_active', true) ? 'checked' : '' }}>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-center mb-3">
        <button class="btn btn-warning fw-bold px-4" type="button" id="addGroupBtn">
            <i class="bi bi-plus-circle me-2"></i>
            Add New Group
        </button>
    </div>
</div>

<style>
    .group-container {
        position: relative;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .group-container:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .schedule-item {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 8px;
    }

    .schedule-item:last-child {
        margin-bottom: 0;
    }

    .delete-group-btn {
        z-index: 10;
    }

    .slide-out {
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .group-container.new {
        animation: slideIn 0.3s ease;
    }
</style>
