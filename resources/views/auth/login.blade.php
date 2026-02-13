@extends('layouts.guest')

@section('title', 'Login - Hola Taxi Ireland')

@section('content')
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <h1><i class="fas fa-taxi"></i> Hola Taxi</h1>
                <p>Welcome Back!</p>
            </div>

            <div class="auth-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
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

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Password <span class="required-mark">*</span>
                        </label>
                        <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                            id="password" name="password" required placeholder="Enter your password">
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me and Forgot Password -->
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="form-check mb-0">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        <a href="{{ route('password.request') }}"
                            style="font-size: 0.9rem; color: #667eea; text-decoration: none; font-weight: 600;">
                            Forgot Password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <div class="mb-3" style="margin-top: 2rem;">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center" style="margin-top: 1.5rem;">
                        <p style="color: #666; margin: 0;">
                            Don't have an account?
                            <a href="{{ route('register') }}">Register here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection