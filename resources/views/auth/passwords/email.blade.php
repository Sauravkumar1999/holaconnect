@extends('layouts.guest')

@section('title', 'Reset Password - Hola Taxi Ireland')

@section('content')
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <h1><i class="fas fa-taxi"></i> Hola Taxi</h1>
                <p>Reset Password</p>
            </div>

            <div class="auth-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email Address <span class="required-mark">*</span>
                        </label>
                        <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                            id="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="your.email@example.com">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mb-3" style="margin-top: 2rem;">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-paper-plane"></i> Send Password Reset Link
                        </button>
                    </div>

                    <!-- Back to Login Link -->
                    <div class="text-center" style="margin-top: 1.5rem;">
                        <p style="color: #666; margin: 0;">
                            Remembered your password?
                            <a href="{{ route('login') }}">Back to Login</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection