@extends('layouts.app')

@section('title', 'Settings - Hola Taxi')

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
        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h2><i class="fas fa-cog"></i> Payment Settings</h2>
            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="registration_payment_amount"
                    style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                    <i class="fas fa-euro-sign"></i> Registration Payment Amount (EUR)
                    <span class="text-danger">*</span>
                </label>
                <div style="display: flex; align-items: center; gap: 1rem; max-width: 500px;">
                    <div style="position: relative; flex: 1;">
                        <i class="fas fa-money-bill-wave"
                            style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #667eea; z-index: 1;"></i>
                        <input type="number" class="form-control @error('registration_payment_amount') is-invalid @enderror"
                            id="registration_payment_amount" name="registration_payment_amount"
                            value="{{ old('registration_payment_amount', $paymentAmount) }}" step="0.01" min="0.01" required
                            placeholder="Enter payment amount" style="padding-left: 40px;">
                    </div>
                    <span style="font-weight: 600; color: #667eea;">EUR</span>
                </div>
                <small style="display: block; margin-top: 0.5rem; color: #666;">
                    <i class="fas fa-info-circle"></i>
                    This amount will be used as the default payment amount for new registrations.
                </small>
                @error('registration_payment_amount')
                    <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                @enderror
            </div>

            <hr style="margin: 2rem 0; border: 0; border-top: 1px solid #eee;">

            <h2><i class="fas fa-certificate"></i> Certificate Settings</h2>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="company_name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                    <i class="fas fa-building"></i> Company Name
                    <span class="text-danger">*</span>
                </label>
                <div style="position: relative; max-width: 500px;">
                    <i class="fas fa-id-card"
                        style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #667eea; z-index: 1;"></i>
                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name"
                        name="company_name" value="{{ old('company_name', $companyName) }}" required
                        placeholder="Enter company name" style="padding-left: 40px;">
                </div>
                <small style="display: block; margin-top: 0.5rem; color: #666;">
                    <i class="fas fa-info-circle"></i>
                    This name will be displayed as the company name on the share certificates.
                </small>
                @error('company_name')
                    <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="company_logo" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                    <i class="fas fa-image"></i> Company Logo (Used on Certificates)
                </label>
                <div style="display: flex; align-items: flex-start; gap: 2rem;">
                    <div style="flex: 1; max-width: 500px;">
                        <input type="file" class="form-control @error('company_logo') is-invalid @enderror"
                            id="company_logo" name="company_logo" accept="image/*">
                        <small style="display: block; margin-top: 0.5rem; color: #666;">
                            <i class="fas fa-info-circle"></i>
                            If no logo is uploaded, a default placeholder will be used.
                        </small>
                        @error('company_logo')
                            <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($companyLogo)
                        <div style="text-align: center;">
                            <p style="font-size: 0.8rem; font-weight: 600; color: #666; margin-bottom: 0.5rem;">Current Logo</p>
                            <div style="background: #f8f9fa; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                                <img src="{{ asset($companyLogo) }}" alt="Current Logo"
                                    style="max-height: 60px; display: block;">
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="director_signature"
                    style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                    <i class="fas fa-signature"></i> Director's Signature Image
                </label>
                <div style="display: flex; align-items: flex-start; gap: 2rem;">
                    <div style="flex: 1; max-width: 500px;">
                        <input type="file" class="form-control @error('director_signature') is-invalid @enderror"
                            id="director_signature" name="director_signature" accept="image/*">
                        <small style="display: block; margin-top: 0.5rem; color: #666;">
                            <i class="fas fa-info-circle"></i>
                            Upload a transparent PNG signature for best results.
                        </small>
                        @error('director_signature')
                            <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($directorSignature)
                        <div style="text-align: center;">
                            <p style="font-size: 0.8rem; font-weight: 600; color: #666; margin-bottom: 0.5rem;">Current
                                Signature</p>
                            <div style="background: #f8f9fa; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                                <img src="{{ asset($directorSignature) }}" alt="Current Signature"
                                    style="max-height: 60px; display: block;">
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="director_name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                    <i class="fas fa-user-tie"></i> Director Name (Printed)
                    <span class="text-danger">*</span>
                </label>
                <div style="position: relative; max-width: 500px;">
                    <i class="fas fa-pen-nib"
                        style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #667eea; z-index: 1;"></i>
                    <input type="text" class="form-control @error('director_name') is-invalid @enderror" id="director_name"
                        name="director_name" value="{{ old('director_name', $directorName) }}" required
                        placeholder="Enter director's name" style="padding-left: 40px;">
                </div>
                <small style="display: block; margin-top: 0.5rem; color: #666;">
                    <i class="fas fa-info-circle"></i>
                    This name will be printed below the signature on the share certificates.
                </small>
                @error('director_name')
                    <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-top: 3rem; border-top: 1px solid #eee; padding-top: 2rem;">
                <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem;">
                    <i class="fas fa-save"></i> Save All Settings
                </button>
                <a href="{{ route('dashboard') }}" style="color: #666; text-decoration: none; margin-left: 1.5rem;">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
@endsection