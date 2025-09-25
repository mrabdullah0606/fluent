@extends('admin.master.master')
@section('title', 'Applications - FluentAll')
@section('content')
    <main class="main-content" id="language-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Applications</h3>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> Back
            </a>
        </div>
        <table id="userTable" class="table table-bordered bg-white shadow-sm">
            <thead class="table-warning">
                <tr>
                    <th>ID</th>
                    <th>Job Title</th>
                    <th>Applicant Name</th>
                    <th>Applicant Email</th>
                    <th>Applicant Phone</th>
                    <th>Applicant Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="language-table-body">
                @foreach ($applicants as $key => $applicant)
                    <tr data-id="{{ $applicant->id }}">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $applicant->career->title }}</td>
                        <td>{{ $applicant->fullName }}</td>
                        <td>{{ $applicant->email }}</td>
                        <td>{{ $applicant->phone }}</td>
                        <td>{{ $applicant->status }}</td>
                        <td class="text-center">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.applicants.edit', $applicant->id) }}" class="btn btn-sm btn-primary"
                                title="Edit applicant">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.applicants.destroy', $applicant->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this applicant?')"
                                    class="btn btn-sm btn-danger" title="Delete applicant">
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
