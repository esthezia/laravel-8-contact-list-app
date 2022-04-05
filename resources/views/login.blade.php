@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card">
            <h4 class="card-header text-center">Login</h4>
            <div class="card-body">
                @if (Session::has('login-error'))
                    <p class="text-danger">{{ Session::get('login-error') }}</p>
                @endif

                <form method="post" action="{{ route('login') }}" autocomplete="off">
                    @csrf

                    <div class="form-group mb-3">
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" required="required" autofocus>
                        @if ($errors->has('email'))
                        <small class="text-danger">{{ $errors->first('email') }}</small>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="required">
                        @if ($errors->has('password'))
                        <small class="text-danger">{{ $errors->first('password') }}</small>
                        @endif
                    </div>

                    <div class="d-grid mx-auto">
                        <button type="submit" class="btn btn-dark btn-block">Login</button>
                    </div>
                </form>
            </div>
        </div>
        <p class="mt20 txt-small text-muted">
            <b>Test user:</b> admin@yahoo.com / admin123
        </p>
    </div>
</div>
@endsection
