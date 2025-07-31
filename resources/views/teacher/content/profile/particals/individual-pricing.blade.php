<div class="settings-card">
    <h5 class="fw-bold text-dark mb-2">
        <i class="bi bi-currency-dollar text-warning me-1"></i> Individual Lesson Pricing
    </h5>
    <p class="text-muted">Set the price for each lesson duration.</p>

    <div class="row g-3">
        @foreach ([30, 60, 90, 120] as $duration)
            <div class="col-md-6 d-flex align-items-center gap-3">
                <label class="mb-0 fw-medium" for="duration_{{ $duration }}">{{ $duration }} mins</label>
                <div class="input-group w-50">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" id="duration_{{ $duration }}"
                        name="duration_{{ $duration }}" placeholder="0.00" step="0.01" min="0"
                        value="{{ old('duration_' . $duration, $settings['duration_' . $duration] ?? '') }}">
                </div>
            </div>
        @endforeach
    </div>
</div>
