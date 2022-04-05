@extends('layout')

@section('content')
<h1 class="h4 mb-4">Contacts</h1>

@if (Session::has('error-message'))
<p class="alert alert-danger">{{ Session::get('error-message') }}</p>
@endif
@if (Session::has('success-message'))
<p class="alert alert-success">{{ Session::get('success-message') }}</p>
@endif

<div class="row">
    <div class="col-sm-6">
        <a href="{{ route('admin.create') }}" class="btn btn-secondary">Create contact</a>
    </div>
    <div class="col-sm-6 text-end" id="contacts-visibility" data-url="{{ route('admin.set-visibility') }}">
        <label class="cursor-pointer">
            <input type="checkbox" name="is-contacts-public" value="1"<?php echo !empty($isContactsPublic) ? ' checked="checked"' : ''; ?> />
            Make contacts publicly available
        </label>
        <img src="{{ url('/images/loading.gif') }}" alt="" width="16" height="16" class="loading invisible js-loading" />
    </div>
</div>

@if (empty($contacts))
    <p class="py-4">There are no contacts.</p>
@else
    <div class="table-responsive py-4">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Email</th>
                    <th scope="col" width="200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                <tr>
                    <td>{{ (int) $contact['id'] }}</td>
                    <td>{{ $contact['name'] }}</td>
                    <td>{{ $contact['phone'] ?? '–' }}</td>
                    <td>{{ $contact['email'] ?? '–' }}</td>
                    <td>
                        <a href="{{ route('admin.create', $contact['id']) }}" class="btn btn-primary btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('admin.delete', $contact['id']) }}" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-confirmation">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path>
                            </svg>
                            Delete
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <small class="text-muted">{{ count($contacts) }} record<?php echo count($contacts) !== 1 ? 's' : ''; ?></small>
    </div>
@endif
@endsection
