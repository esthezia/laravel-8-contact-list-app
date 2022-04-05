@extends('layout')

@section('content')
<h1 class="h4 mb-4">
    @if (!empty($isRestore))
        Edit contact and restore
    @else
        Create / edit contact
    @endif
</h1>

@if ($errors->any())
<div class="alert alert-danger">
    @foreach ($errors->all() as $error)
        {{ $error }}<br />
    @endforeach
</div>
@endif

<div class="row">
    <div class="col-md-6">
        <form method="post" action="{{ route(!empty($isRestore) ? 'admin.edit-restore' : 'admin.create', $contact ? $contact->id : null) }}" autocomplete="off">
            @csrf

            <div class="form-group mb-3">
                <label for="contact-name" class="form-label">Name</label>
                <input type="text" name="name" value="{{ $contact ? $contact->name : old('name') }}" id="contact-name" class="form-control" required="required" autofocus>
            </div>
            <div class="form-group mb-3">
                <label for="contact-phone" class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ $contact ? $contact->phone : old('phone') }}" id="contact-phone" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="contact-email" class="form-label">Email</label>
                <input type="email" name="email" value="{{ $contact ? $contact->email : old('email') }}" id="contact-email" class="form-control">
            </div>
            <p class="mt20 txt-small text-muted"><b>Note:</b> Either phone or email must be provided.</p>
            <div class="form-group mb-3">
                <label for="contact-notes" class="form-label">Notes</label>
                <textarea name="notes" id="contact-notes" cols="30" rows="4" class="form-control">{{ $contact ? $contact->notes : old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">
                @if (!empty($isRestore))
                    Save and restore
                @else
                    Save
                @endif
            </button>
        </form>
    </div>
</div>
@endsection
