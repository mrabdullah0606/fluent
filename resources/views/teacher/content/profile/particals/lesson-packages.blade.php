<div class="settings-card">
    <h5 class="fw-bold text-dark mb-3">
        <i class="bi bi-box text-warning me-1"></i> Lesson Packages
    </h5>
    <p class="text-muted">Create bundles of lessons to offer a discount.</p>

    @for ($i = 1; $i <= 3; $i++)
        <div class="package-container">
            <h6 class="fw-bold mb-3">Package {{ $i }}</h6>

            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small" for="package_{{ $i }}_lessons">Number of Lessons</label>
                    <input type="number" name="packages[{{ $i }}][number_of_lessons]"
                        id="package_{{ $i }}_lessons" class="form-control" min="1"
                        value="{{ old('packages.' . $i . '.number_of_lessons', $packages[$i]['number_of_lessons'] ?? '') }}"
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label small" for="package_{{ $i }}_duration">Duration per Lesson
                        (mins)</label>
                    <select name="packages[{{ $i }}][duration_per_lesson]"
                        id="package_{{ $i }}_duration" class="form-select" required>
                        @foreach ([30, 60, 90, 120] as $duration)
                            <option value="{{ $duration }}"
                                {{ old('packages.' . $i . '.duration_per_lesson', $packages[$i]['duration_per_lesson'] ?? '') == $duration ? 'selected' : '' }}>
                                {{ $duration }} mins
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small" for="package_{{ $i }}_price">Total Price ($)</label>
                    <input type="number" name="packages[{{ $i }}][price]"
                        id="package_{{ $i }}_price" class="form-control" step="0.01" min="0"
                        value="{{ old('packages.' . $i . '.price', $packages[$i]['price'] ?? '') }}" readonly>
                </div>

            </div>

            <input type="hidden" name="packages[{{ $i }}][name]" value="Package {{ $i }}">

            <!-- Toggle Switch -->
            <div class="form-check form-switch position-absolute top-0 end-0 mt-2 me-3">
                <input class="form-check-input bg-warning border" type="checkbox"
                    name="packages[{{ $i }}][is_active]" id="package_{{ $i }}_active"
                    value="1"
                    {{ old('packages.' . $i . '.is_active', $packages[$i]['is_active'] ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="package_{{ $i }}_active">Active</label>
            </div>
        </div>
    @endfor
</div>
