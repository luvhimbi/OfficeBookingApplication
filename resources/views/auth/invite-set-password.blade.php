@extends('Layout.app')

@section('title','Set Password')

@section('content')
    <div class="container mt-5">
        <div class="card shadow border-0">
            <div class="card-body">
                <h4>Set Your Password</h4>

                <form action="{{ route('invite.complete',$invite->token) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button class="btn btn-primary">Complete Registration</button>
                </form>
            </div>
        </div>
    </div>
@endsection
