@extends('admin.master.master')
@section('title', 'Language Management - FluentAll')
@section('content')
    <main class="main-content" id="language-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">{{ __('welcome.key_726') }}</h3>
            <a class="btn btn-warning" href="{{ route('admin.languages.create') }}" id="addUserBtn">
                <i class="bi bi-plus-circle me-1"></i> {{ __('welcome.key_727') }}
            </a>
        </div>
        <table id="userTable2" class="table table-bordered bg-white shadow-sm">
            <thead class="table-warning">
                <tr>
                    <th>{{ __('welcome.key_598') }}</th>
                    <th>{{ __('welcome.key_728') }}</th>
                    <th>{{ __('welcome.key_729') }}</th>
                    <th class="text-center">{{ __('welcome.key_604') }}</th>
                </tr>
            </thead>
            <tbody id="language-table-body">
                @foreach ($languages as $key => $language)
                    <tr data-id="{{ $language->id }}">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $language->name }}</td>
                        <td>{{ $language->symbol }}</td>
                        <td class="text-center">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.languages.edit', $language->id) }}" class="btn btn-sm btn-primary"
                                title="Edit Language">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.languages.destroy', $language->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this language?')"
                                    class="btn btn-sm btn-danger" title="Delete Language">
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
