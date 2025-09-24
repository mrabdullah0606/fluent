@extends('admin.master.master')
@section('title', 'Reviews - FluentAll')
@section('content')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #4CAF50;
        }

        input:checked+.slider:before {
            transform: translateX(22px);
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .success-flash {
            background-color: #d4edda !important;
            transition: background-color 0.3s ease;
        }

        .error-flash {
            background-color: #f8d7da !important;
            transition: background-color 0.3s ease;
        }
    </style>

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">{{ __('welcome.key_788') }}</h3>
        </div>

        <!-- Success/Error Messages -->
        <div id="flashMessage" class="alert d-none" role="alert"></div>

        <div class="table-responsive">
            <table id="userTable" class="table table-bordered bg-white shadow-sm">
                <thead class="table-warning">
                    <tr>
                        <th>#</th>
                        <th>{{ __('welcome.key_642') }}</th>
                        <th>{{ __('welcome.key_521') }}</th>
                        <th>{{ __('welcome.key_789') }}</th>
                        <th>{{ __('welcome.key_487') }}</th>
                        <th>{{ __('welcome.key_718') }}</th>
                        <th>{{ __('welcome.key_790') }}</th>
                        <th class="text-center">{{ __('welcome.key_604') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $index =>  $review)
                        <tr id="review-row-{{ $review->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $review->student->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $review->student->email }}</small>
                            </td>
                            <td>
                                <strong>{{ $review->teacher->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $review->teacher->email }}</small>
                            </td>
                            <td>
                                <div class="review-content" style="max-width: 250px;">
                                    {{ Str::limit($review->comment, 100) }}
                                    @if (strlen($review->comment) > 100)
                                        <br>
                                        <button class="btn btn-link btn-sm p-0 toggle-full-review"
                                            data-full-text="{{ $review->comment }}"
                                            data-short-text="{{ Str::limit($review->comment, 100) }}">
                                            {{ __('welcome.key_793') }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : '' }}"></i>
                                    @endfor
                                    <span class="ms-2 text-muted">({{ $review->rating }}/5)</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $review->is_approved ? 'success' : 'warning' }}">
                                    {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <label class="switch">
                                    <input type="checkbox" class="approve-toggle" data-id="{{ $review->id }}"
                                        {{ $review->is_approved ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this review? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete Review">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    {{-- @if ($reviews->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-chat-left-text-fill" style="font-size: 2rem;"></i>
                                <br>
                                No reviews found
                            </td>
                        </tr>
                    @endif --}}
                </tbody>
            </table>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const flashMessage = document.getElementById("flashMessage");

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

            function showFlashMessage(message, type = 'success') {
                flashMessage.className = `alert alert-${type}`;
                flashMessage.textContent = message;
                flashMessage.classList.remove('d-none');
                setTimeout(() => flashMessage.classList.add('d-none'), 3000);
            }

            function flashRow(reviewId, type = 'success') {
                const row = document.getElementById(`review-row-${reviewId}`);
                if (row) {
                    row.classList.add(type === 'success' ? 'success-flash' : 'error-flash');
                    setTimeout(() => row.classList.remove('success-flash', 'error-flash'), 2000);
                }
            }

            // Toggle approval
            document.querySelectorAll(".approve-toggle").forEach(toggle => {
                toggle.addEventListener("change", async function() {
                    const reviewId = this.dataset.id;
                    const isApproved = this.checked ? 1 : 0;
                    const row = document.getElementById(`review-row-${reviewId}`);
                    const statusBadge = row.querySelector('.badge');

                    row.classList.add('loading');
                    this.disabled = true;

                    try {
                        const res = await fetch(`/admin/reviews/${reviewId}/toggle`, {
                            method: "PATCH",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken
                            },
                            body: JSON.stringify({
                                is_approved: isApproved
                            })
                        });

                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Error updating review');

                        statusBadge.className =
                            `badge bg-${isApproved ? 'success' : 'warning'}`;
                        statusBadge.textContent = isApproved ? 'Approved' : 'Pending';

                        showFlashMessage(data.message, 'success');
                        flashRow(reviewId, 'success');
                    } catch (err) {
                        this.checked = !this.checked;
                        showFlashMessage(err.message, 'danger');
                        flashRow(reviewId, 'error');
                    } finally {
                        row.classList.remove('loading');
                        this.disabled = false;
                    }
                });
            });

            // Read more/less toggle
            document.querySelectorAll('.toggle-full-review').forEach(btn => {
                btn.addEventListener('click', function() {
                    const reviewContent = this.parentElement;
                    const isExpanded = this.textContent === 'Read less';
                    reviewContent.firstChild.textContent = isExpanded ? this.dataset.shortText :
                        this.dataset.fullText;
                    this.textContent = isExpanded ? 'Read more' : 'Read less';
                });
            });
        });
    </script>

@endsection
