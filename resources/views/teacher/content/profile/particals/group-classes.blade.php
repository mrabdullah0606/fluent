<div class="settings-card" id="groupClassesContainer">
    <h5 class="fw-bold text-dark mb-3">
        <i class="bi bi-people-fill fs-3 text-warning me-1"></i> Group Classes
    </h5>
    <p class="text-muted">Set up and manage your group classes.</p>

    @forelse($groups ?? [collect()] as $index => $group)
        <div class="group-container">
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
                        value="{{ old('groups.' . $index . '.max_students', $group['max_students'] ?? '') }}" required>
                </div>

                <div class="col-6 col-md-3">
                    <label class="form-label small" for="group_{{ $index }}_price">Price per Student ($)</label>
                    <input type="number" class="form-control" name="groups[{{ $index }}][price_per_student]"
                        id="group_{{ $index }}_price" step="0.01" min="0" placeholder="0.00"
                        value="{{ old('groups.' . $index . '.price_per_student', $group['price_per_student'] ?? '') }}"
                        required>
                </div>
            </div>

            <!-- Days -->
            <div class="mb-2 fw-bold text-dark mt-3">Days</div>
            <div class="d-flex flex-wrap gap-2 days-container">
                @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                    @php
                        $isSelected = in_array($day, old('groups.' . $index . '.days', $group['days'] ?? []));
                    @endphp
                    <label class="btn btn-outline-secondary day-btn {{ $isSelected ? 'selected' : '' }}">
                        <input type="checkbox" name="groups[{{ $index }}][days][]" value="{{ $day }}"
                            class="d-none" {{ $isSelected ? 'checked' : '' }}>
                        {{ $day }}
                    </label>
                @endforeach
            </div>

            <!-- Toggle Switch -->
            <div class="form-check form-switch position-absolute top-0 end-0 mt-2 me-3">
                <input class="form-check-input bg-warning border" type="checkbox"
                    name="groups[{{ $index }}][is_active]" id="group_{{ $index }}_active" value="1"
                    {{ old('groups.' . $index . '.is_active', $group['is_active'] ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="group_{{ $index }}_active">Active</label>
            </div>
        </div>
    @empty
        <div class="group-container">
            <input type="text" name="groups[0][title]" class="form-control fw-bold editable-heading mb-3"
                value="French for Beginners (A1)" required>

            <!-- Description Textarea -->
            <div class="mb-3">
                <label class="form-label small fw-bold text-dark">Description</label>
                <textarea class="form-control" name="groups[0][description]" rows="3"
                    placeholder="Describe your group class (e.g., curriculum, teaching method, target audience...)"
                    style="resize: vertical;"></textarea>
                <div class="form-text text-muted small">
                    Optional: Help students understand what this class offers
                </div>
            </div>

            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <label class="form-label small">Duration</label>
                    <select class="form-select" name="groups[0][duration_per_class]" required>
                        <option value="60">60 min</option>
                        <option value="90">90 min</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small">Lessons/week</label>
                    <select class="form-select" name="groups[0][lessons_per_week]" required>
                        @for ($j = 1; $j <= 5; $j++)
                            <option value="{{ $j }}">{{ $j }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small">Max Students</label>
                    <input type="number" class="form-control" name="groups[0][max_students]" min="1"
                        max="100" placeholder="Enter max students" required>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small">Price per Student ($)</label>
                    <input type="number" class="form-control" name="groups[0][price_per_student]" step="0.01"
                        placeholder="0.00" required>
                </div>
            </div>

            <!-- Days -->
            <div class="mb-2 fw-bold text-dark mt-3">Days</div>
            <div class="d-flex flex-wrap gap-2 days-container">
                @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                    <label class="btn btn-outline-secondary day-btn">
                        <input type="checkbox" name="groups[0][days][]" value="{{ $day }}" class="d-none">
                        {{ $day }}
                    </label>
                @endforeach
            </div>

            <!-- Toggle Switch -->
            <div class="form-check form-switch position-absolute top-0 end-0 mt-2 me-3">
                <input class="form-check-input bg-warning border" type="checkbox" name="groups[0][is_active]"
                    value="1" checked>
            </div>
        </div>
    @endforelse

    <div class="d-flex justify-content-center mb-3">
        <button class="btn btn-warning fw-bold px-4" type="button" id="addGroupBtn">
            <i class="bi bi-plus-circle me-2"></i>
            Add New Group
        </button>
    </div>
</div>
