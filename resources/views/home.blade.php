@extends('layout')

@section('content')

<p>Welcome!</p>

@if (!empty($isContactsPublic))
    <p><a href="{{ route('contacts') }}" class="link">&rsaquo; See all contacts</a></p>
@endif

@endsection
