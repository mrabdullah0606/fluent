@extends('admin.master.master')
@section('title', 'User Management - FluentAll')
@section('content')
    <main class="main-content" id="language-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">User Management</h3>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold"><a href="{{ route('admin.careers.create') }}" class="btn btn-secondary">Add New Job</a></h3>
            <a href="{{ route('admin.careers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> Back
            </a>
        </div>
        <table id="userTable" class="table table-bordered bg-white shadow-sm">
            <thead class="table-warning">
                <tr>
                    <th>ID</th>
                    <th>Job Title</th>
                    <th>Salary</th>
                    <th>Job Type</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="language-table-body">
                @foreach ($careers as $key => $career)
                    <tr data-id="{{ $career->id }}">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $career->title }}</td>
                        <td>{{ $career->salary }}</td>
                        <td>{{ $career->type }}</td>
                        <td>{{ $career->location }}</td>
                        <td>{{ $career->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="text-center">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.careers.edit', $career->id) }}" class="btn btn-sm btn-primary"
                                title="Edit career">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.careers.destroy', $career->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this career?')"
                                    class="btn btn-sm btn-danger" title="Delete career">
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
