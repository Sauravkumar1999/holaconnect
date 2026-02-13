@extends('layouts.guest')

@section('title', 'Set New Password - Hola Taxi Ireland')

@section('content')
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <h1><i class="fas fa-taxi"></i> Hola Taxi</h1>
                <p>Set New Password</p>
            </div>

            <div class="auth-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email Address <span class="required-mark">*</span>
                        </label>
                        <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                            id="email" name="email" value="{{ $email ?? old('email') }}" required autofocus
                            placeholder="your.email@example.com" readonly>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            New Password <span class="required-mark">*</span>
                        </label>
                        <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                            id="password" name="password" required placeholder="Enter new password">
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">
                            Confirm Password <span class="required-mark">*</span>
                        </label>
                        <input type="password" class="form-control form-control-lg" id="password-confirm"
                            name="password_confirmation" required placeholder="Confirm new password">
                    </div>

                    <!-- Submit Button -->
                    <div class="mb-3" style="margin-top: 2rem;">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-key"></i> Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection