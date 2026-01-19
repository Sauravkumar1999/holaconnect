@extends('layouts.guest')

@section('title', 'Register - Hola Taxi Ireland')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1><i class="fas fa-taxi"></i> Shareholder Registration</h1>
            <p>Fill The Form</p>
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
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin: 0.5rem 0 0; padding-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registrationForm">
                @csrf

                <!-- Full Name -->
                <div class="form-group">
                    <label for="name" class="form-label">
                        Full Name <span class="required-mark">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus
                           placeholder="Enter your full name">
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">
                        Email Address <span class="required-mark">*</span>
                    </label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required
                           placeholder="your.email@example.com">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        Password <span class="required-mark">*</span>
                    </label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           required
                           placeholder="Minimum 8 characters">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">
                        Confirm Password <span class="required-mark">*</span>
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required
                           placeholder="Re-enter your password">
                </div>

                <!-- Phone Number -->
                <div class="form-group">
                    <label for="phone" class="form-label">
                        Phone Number <span class="required-mark">*</span>
                    </label>
                    <input type="tel" 
                           class="form-control @error('phone') is-invalid @enderror" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone') }}" 
                           required
                           placeholder="+353 XX XXX XXXX">
                    @error('phone')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- PSP Number -->
                <div class="form-group">
                    <label for="psp_number" class="form-label">
                        PSP Number
                    </label>
                    <input type="text" 
                           class="form-control @error('psp_number') is-invalid @enderror" 
                           id="psp_number" 
                           name="psp_number" 
                           value="{{ old('psp_number') }}"
                           placeholder="Enter PSP number (optional)">
                    @error('psp_number')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Taxi Driver ID Number -->
                <div class="form-group">
                    <label for="taxi_driver_id" class="form-label">
                        Taxi Driver ID Number
                    </label>
                    <input type="text" 
                           class="form-control @error('taxi_driver_id') is-invalid @enderror" 
                           id="taxi_driver_id" 
                           name="taxi_driver_id" 
                           value="{{ old('taxi_driver_id') }}"
                           placeholder="Enter taxi driver ID (optional)">
                    @error('taxi_driver_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Upload Taxi Driver Dashboard Number Document -->
                <div class="form-group">
                    <label for="document_dashboard" class="form-label">
                        Upload Taxi Driver Dashboard Number Document <span class="required-mark">*</span>
                    </label>
                    <input type="file" 
                           class="form-control @error('document_dashboard') is-invalid @enderror" 
                           id="document_dashboard" 
                           name="document_dashboard" 
                           required
                           accept=".pdf,.jpg,.jpeg,.png">
                    <small class="text-muted">Accepted formats: PDF, JPG, PNG (Max: 2MB)</small>
                    @error('document_dashboard')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Upload Proof of Identity Document -->
                <div class="form-group">
                    <label for="document_identity" class="form-label">
                        Upload Proof of Identity Document <span class="required-mark">*</span>
                    </label>
                    <input type="file" 
                           class="form-control @error('document_identity') is-invalid @enderror" 
                           id="document_identity" 
                           name="document_identity" 
                           required
                           accept=".pdf,.jpg,.jpeg,.png">
                    <small class="text-muted">Accepted formats: PDF, JPG, PNG (Max: 2MB)</small>
                    @error('document_identity')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Payment Type Selection -->
                <div class="form-group">
                    <label class="form-label">
                        Payment Type <span class="required-mark">*</span>
                    </label>
                    <div class="payment-type-wrapper">
                        <label class="radio-card" id="prePaymentCard">
                            <input type="radio" 
                                   name="payment_type" 
                                   value="pre_payment" 
                                   id="pre_payment"
                                   {{ old('payment_type') == 'pre_payment' ? 'checked' : '' }}
                                   required>
                            <label for="pre_payment" style="cursor: pointer; margin: 0;">
                                <i class="fas fa-money-check-alt"></i> Pre Payment
                            </label>
                        </label>
                        <label class="radio-card" id="newPaymentCard">
                            <input type="radio" 
                                   name="payment_type" 
                                   value="new_payment" 
                                   id="new_payment"
                                   {{ old('payment_type') == 'new_payment' ? 'checked' : '' }}
                                   required>
                            <label for="new_payment" style="cursor: pointer; margin: 0;">
                                <i class="fas fa-credit-card"></i> New Payment
                            </label>
                        </label>
                    </div>
                    @error('payment_type')
                        <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Upload Payment Receipt (Conditional) -->
                <div class="form-group" id="paymentReceiptField" style="display: none;">
                    <label for="document_payment_receipt" class="form-label">
                        Upload Payment Receipt <span class="required-mark">*</span>
                    </label>
                    <input type="file" 
                           class="form-control @error('document_payment_receipt') is-invalid @enderror" 
                           id="document_payment_receipt" 
                           name="document_payment_receipt"
                           accept=".pdf,.jpg,.jpeg,.png">
                    <small class="text-muted">Accepted formats: PDF, JPG, PNG (Max: 2MB)</small>
                    @error('document_payment_receipt')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Agreements -->
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input @error('terms_agreed') is-invalid @enderror" 
                               id="terms_agreed" 
                               name="terms_agreed" 
                               value="1"
                               {{ old('terms_agreed') ? 'checked' : '' }}
                               required>
                        <label class="form-check-label" for="terms_agreed">
                            I agree to the <strong>"Hola Taxi Ireland"</strong> terms and conditions <span class="required-mark">*</span>
                        </label>
                    </div>
                    @error('terms_agreed')
                        <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input @error('share_certificate_agreed') is-invalid @enderror" 
                               id="share_certificate_agreed" 
                               name="share_certificate_agreed" 
                               value="1"
                               {{ old('share_certificate_agreed') ? 'checked' : '' }}
                               required>
                        <label class="form-check-label" for="share_certificate_agreed">
                            I agree to the <strong>"Share Certificate Issue"</strong> <span class="required-mark">*</span>
                        </label>
                    </div>
                    @error('share_certificate_agreed')
                        <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="form-group" style="margin-top: 2rem;">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-user-plus"></i> Register Now
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center" style="margin-top: 1.5rem;">
                    <p style="color: #666; margin: 0;">
                        Already have an account? 
                        <a href="{{ route('login') }}">Login here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const prePaymentRadio = document.getElementById('pre_payment');
        const newPaymentRadio = document.getElementById('new_payment');
        const paymentReceiptField = document.getElementById('paymentReceiptField');
        const paymentReceiptInput = document.getElementById('document_payment_receipt');
        const prePaymentCard = document.getElementById('prePaymentCard');
        const newPaymentCard = document.getElementById('newPaymentCard');

        function togglePaymentReceipt() {
            if (prePaymentRadio.checked) {
                paymentReceiptField.style.display = 'block';
                paymentReceiptInput.required = true;
                prePaymentCard.classList.add('active');
                newPaymentCard.classList.remove('active');
            } else if (newPaymentRadio.checked) {
                paymentReceiptField.style.display = 'none';
                paymentReceiptInput.required = false;
                paymentReceiptInput.value = '';
                newPaymentCard.classList.add('active');
                prePaymentCard.classList.remove('active');
            }
        }

        // Add event listeners
        prePaymentRadio.addEventListener('change', togglePaymentReceipt);
        newPaymentRadio.addEventListener('change', togglePaymentReceipt);

        // Initialize on page load
        togglePaymentReceipt();

        // Make radio cards clickable
        prePaymentCard.addEventListener('click', function() {
            prePaymentRadio.checked = true;
            togglePaymentReceipt();
        });

        newPaymentCard.addEventListener('click', function() {
            newPaymentRadio.checked = true;
            togglePaymentReceipt();
        });
    });
</script>
@endpush
