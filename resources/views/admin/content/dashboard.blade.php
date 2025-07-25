@extends('admin.master.master')

@section('content')
    <!-- Sidebar -->
    <main class="main-content" id="main-content">
        <h3 class="fw-bold">Welcome Back, Admin!</h3>
        <p class="text-muted">Here's what's happening on your FluentAll dashboard today.</p>
        <div class="row g-4 my-4">
            <!-- Available Balance Card -->
            <div class="col-12 col-md-4">
                <div class="p-3 position-relative card-border bg-white h-100">
                    <div class="icon-end"><i class="bi bi-credit-card"></i></div>
                    <p class="text-muted mb-1">Available Balance</p>
                    <h4 class="fw-bold mb-1">$2,435.50</h4>
                    <small class="text-muted">+ $120.00 since last withdrawal</small>
                </div>
            </div>
            <!-- Total Students Card -->
            <div class="col-12 col-md-4">
                <div class="p-3 position-relative card-border bg-white h-100">
                    <div class="icon-end"><i class="bi bi-people"></i></div>
                    <p class="text-muted mb-1">Total Students</p>
                    <h4 class="fw-bold mb-1">23</h4>
                    <small class="text-muted">+2 new this month</small>
                </div>
            </div>
            <!-- Lessons This Week Card -->
            <div class="col-12 col-md-4">
                <div class="p-3 position-relative card-border bg-white h-100">
                    <div class="icon-end"><i class="bi bi-clock"></i></div>
                    <p class="text-muted mb-1">Lessons This Week</p>
                    <h4 class="fw-bold mb-1">12</h4>
                    <small class="text-muted">3 completed, 9 upcoming</small>
                </div>
            </div>
        </div>
        <!-- Alert -->
        <div class="alert alert-custom d-flex align-items-center mt-3" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2 text-warning"></i>
            <div>
                Don't forget to update your availability for next week in your <strong>calendar</strong>!
            </div>
        </div>
        <div class="container-fluid px-3 px-md-4 px-lg-5 py-5">
            <h4 class="fw-bold mb-4">Upcoming Lessons</h4>
            <!-- Lesson Card 1 -->
            <div class="lesson-card p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-circle">J</div>
                    <div>
                        <div class="fw-bold">John Doe</div>
                        <div class="text-muted">English - 60 min</div>
                        <div class="time-text"><i class="bi bi-clock"></i> Today at 14:00</div>
                    </div>
                </div>
                <a href="#" class="btn btn-yellow d-flex align-items-center mt-3 mt-sm-0">
                    <i class="bi bi-camera-video me-1"></i> Join Lesson
                </a>
            </div>
            <!-- Lesson Card 2 -->
            <div class="lesson-card p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-circle">M</div>
                    <div>
                        <div class="fw-bold">Maria Garcia</div>
                        <div class="text-muted">Spanish - 30 min (Trial)</div>
                        <div class="time-text"><i class="bi bi-clock"></i> Today at 16:30</div>
                    </div>
                </div>
                <a href="#" class="btn btn-yellow d-flex align-items-center mt-3 mt-sm-0">
                    <i class="bi bi-camera-video me-1"></i> Join Lesson
                </a>
            </div>
            <!-- Lesson Card 3 -->
            <div class="lesson-card p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-circle">K</div>
                    <div>
                        <div class="fw-bold">Kenji Tanaka</div>
                        <div class="text-muted">Japanese - 60 min</div>
                        <div class="time-text"><i class="bi bi-clock"></i> Tomorrow at 10:00</div>
                    </div>
                </div>
                <a href="#" class="btn btn-yellow d-flex align-items-center mt-3 mt-sm-0">
                    <i class="bi bi-camera-video me-1"></i> Join Lesson
                </a>
            </div>
            <!-- Lesson Card 4 -->
            <div class="lesson-card p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-circle">F</div>
                    <div>
                        <div class="fw-bold">French Group A1</div>
                        <div class="text-muted">French - 90 min</div>
                        <div class="time-text"><i class="bi bi-clock"></i> Tomorrow at 18:00</div>
                    </div>
                </div>
                <a href="#" class="btn btn-yellow d-flex align-items-center mt-3 mt-sm-0">
                    <i class="bi bi-camera-video me-1"></i> Join Lesson
                </a>
            </div>
            <!-- Show More Button -->
            <div class="text-center show-more-btn">
                <button class="btn btn-outline-secondary">Show More (1 more)</button>
            </div>
        </div>
    </main>
    <!-- Hidden CRUD section -->
    <main class="main-content d-none" id="user-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">User Management</h3>
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addUserModal" id="addUserBtn">
                <i class="bi bi-plus-circle me-1"></i> Add User
            </button>
        </div>

        <table class="table table-bordered bg-white shadow-sm">
            <thead class="table-warning">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Email Verified</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="user-table-body">
                <!-- Sample row -->
                {{-- @foreach ($users as $user)
                            <tr data-id="{{ $user->id }}">
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td>{{ $user->email_verified_at ? 'Yes' : 'No' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary editUserBtn"
                                        data-user='@json($user)'>Edit</button>
                                    <button class="btn btn-sm btn-danger deleteUserBtn">Delete</button>
                                </td>
                            </tr>
                        @endforeach --}}
            </tbody>
        </table>
    </main>
    <button class="btn btn-warning rounded-circle shadow position-fixed bottom-0 end-0 m-4"
        style="width:60px; height:60px;">
        <i class="bi bi-chat-dots fs-4 text-dark"></i>
    </button>
    <!-- Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="userForm" method="POST" action="#re') }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add/Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="editIndex" />

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" id="userName" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="userEmail" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" id="userPassword" required />
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="userEmailVerified" />
                        <label class="form-check-label" for="userEmailVerified">
                            Email Verified
                        </label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-control" id="userRole">
                            <option value="Student">Student</option>
                            <option value="Teacher">Teacher</option>
                        </select>
                    </div>
                </div> <!-- ✅ End modal-body -->

                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Save</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Show modal for Add
            $('#addUserBtn').on('click', function() {
                resetForm();
                $('#addUserModalLabel').text('Add User');
                $('#userForm').data('action', 'add');
                $('#addUserModal').modal('show');
            });

            // Show modal for Edit
            $('.editUserBtn').on('click', function() {
                const user = $(this).data('user');
                $('#editIndex').val(user.id);
                $('#userName').val(user.name);
                $('#userEmail').val(user.email);
                $('#userRole').val(user.role);
                $('#userPassword').val('');
                $('#userEmailVerified').prop('checked', !!user.email_verified_at);

                $('#addUserModalLabel').text('Edit User');
                $('#userForm').data('action', 'edit');
                $('#addUserModal').modal('show');
            });

            // Save user (Add or Edit)
            // $('#userForm').on('submit', function (e) {
            //   e.preventDefault();

            //   const action = $(this).data('action');
            //   const userId = $('#editIndex').val();
            //   const url = action === 'add'
            //     ? "#re') }}"
            //     : `/admin/users/${userId}`;
            //   const method = action === 'add' ? 'POST' : 'PUT';

            //   $.ajax({
            //     url: url,
            //     method: method,
            //     data: {
            //       _token: "{{ csrf_token() }}",
            //       name: $('#userName').val(),
            //       email: $('#userEmail').val(),
            //       password: $('#userPassword').val(),
            //       role: $('#userRole').val().toLowerCase(),
            //       email_verified: $('#userEmailVerified').is(':checked') ? 1 : 0,
            //       _method: method // Laravel spoof for PUT
            //     },
            //     success: function (res) {
            //       location.reload(); // Or update DOM manually
            //     },
            //     error: function (xhr) {
            //       alert('Something went wrong!');
            //       console.log(xhr.responseText);
            //     }
            //   });
            // });

            // Delete user
            $('.deleteUserBtn').on('click', function() {
                const row = $(this).closest('tr');
                const userId = row.data('id');
                if (confirm('Are you sure you want to delete this user?')) {
                    $.ajax({
                        url: `/admin/users/${userId}`,
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE',
                        },
                        success: function() {
                            location.reload(); // Or remove row from DOM
                        },
                        error: function() {
                            alert('Error deleting user.');
                        }
                    });
                }
            });

            function resetForm() {
                $('#userForm')[0].reset();
                $('#editIndex').val('');
            }
        });
    </script>
@endsection
