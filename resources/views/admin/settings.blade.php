@extends('layouts.app')

@section('title', 'Settings - Hola Connect')

@section('page-title', 'Settings')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 0.5rem 0 0; padding-left: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <h2><i class="fas fa-cog"></i> Payment Settings</h2>

        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="registration_payment_amount" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                    <i class="fas fa-euro-sign"></i> Registration Payment Amount (EUR)
                    <span class="text-danger">*</span>
                </label>
                <div style="display: flex; align-items: center; gap: 1rem; max-width: 500px;">
                    <div style="position: relative; flex: 1;">
                        <i class="fas fa-money-bill-wave" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #667eea; z-index: 1;"></i>
                        <input 
                            type="number" 
                            class="form-control @error('registration_payment_amount') is-invalid @enderror" 
                            id="registration_payment_amount" 
                            name="registration_payment_amount" 
                            value="{{ old('registration_payment_amount', $paymentAmount) }}" 
                            step="0.01" 
                            min="0.01" 
                            required
                            placeholder="Enter payment amount"
                            style="padding-left: 40px;">
                    </div>
                    <span style="font-weight: 600; color: #667eea;">EUR</span>
                </div>
                <small style="display: block; margin-top: 0.5rem; color: #666;">
                    <i class="fas fa-info-circle"></i> 
                    This amount will be used as the default payment amount for new registrations. 
                    Users can change this amount during registration.
                </small>
                @error('registration_payment_amount')
                    <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="info-item" style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #667eea;">
                <label style="font-weight: 600; color: #333; margin-bottom: 0.5rem; display: block;">
                    <i class="fas fa-info-circle"></i> Current Amount
                </label>
                <p style="font-size: 1.5rem; font-weight: 700; color: #667eea; margin: 0;">
                    €{{ number_format($paymentAmount, 2) }}
                </p>
            </div>

            <div class="form-group" style="margin-top: 2rem;">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Save Settings
                </button>
                <a href="{{ route('dashboard') }}" style="color: #666; text-decoration: none; margin-left: 1rem;">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
