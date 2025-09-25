@extends('admin.master.master')
@section('title', 'User Management - FluentAll')
@section('content')
    <main class="main-content" id="language-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">User Management</h3>
        </div>
         <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold"><a href="{{ route('admin.users.create') }}" class="btn btn-secondary">Add New User</a></h3>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> Back
            </a>
        </div>
        <table id="userTable" class="table table-bordered bg-white shadow-sm">
            <thead class="table-warning">
                <tr> 
                    <th>ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="language-table-body">
                @foreach ($users as $key => $user)
                    <tr data-id="{{ $user->id }}">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->role }}</td>
                        <td class="text-center">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary"
                                title="Edit user">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this user?')"
                                    class="btn btn-sm btn-danger" title="Delete user">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
@endsection
