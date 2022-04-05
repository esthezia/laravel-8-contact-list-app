@extends('layout')

@section('content')

@if (empty($contacts))
    <p>There are no contacts.</p>
@else

    <ul class="list-group list-group-flush">
        @foreach ($contacts as $contact)
        <li class="list-group-item">
            <?php
                echo '<h5 class="mb-1">' . htmlspecialchars($contact['name']) . '</h5>';
                echo '<p class="m0 txt-small text-muted">';
                if (!empty($contact['phone'])) {
                    echo '<b>Phone:</b> ' . htmlspecialchars($contact['phone']) . '<br />';
                }
                if (!empty($contact['email'])) {
                    echo '<b>Email:</b> ' . htmlspecialchars($contact['email']) . '<br />';
                }
                if (!empty($contact['notes'])) {
                    echo '<b>Notes:</b> ' . str_replace("\n", "<br />", htmlspecialchars($contact['notes']));
                }
                echo '</p>';
            ?>
        </li>
        @endforeach
    </ul>
@endif

@endsection
