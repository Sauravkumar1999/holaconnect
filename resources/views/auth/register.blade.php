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

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registrationForm">
                    @csrf
                    <input type="hidden" name="payment_order_code" id="payment_order_code" value="">

                    <!-- Full Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            Full Name <span class="required-mark">*</span>
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" required autofocus
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
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" required placeholder="your.email@example.com">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            Password <span class="required-mark">*</span>
                        </label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" required placeholder="Minimum 8 characters">
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            Confirm Password <span class="required-mark">*</span>
                        </label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            required placeholder="Re-enter your password">
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group">
                        <label for="phone" class="form-label">
                            Phone Number <span class="required-mark">*</span>
                        </label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone"
                            name="phone" value="{{ old('phone') }}" required placeholder="+353 XX XXX XXXX">
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- PSP Number -->
                    <div class="form-group">
                        <label for="psp_number" class="form-label">
                            PSP Number
                        </label>
                        <input type="text" class="form-control @error('psp_number') is-invalid @enderror" id="psp_number"
                            name="psp_number" value="{{ old('psp_number') }}" placeholder="Enter PSP number (optional)">
                        @error('psp_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Taxi Driver ID Number -->
                    <div class="form-group">
                        <label for="taxi_driver_id" class="form-label">
                            Taxi Driver ID Number
                        </label>
                        <input type="text" class="form-control @error('taxi_driver_id') is-invalid @enderror"
                            id="taxi_driver_id" name="taxi_driver_id" value="{{ old('taxi_driver_id') }}"
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
                        <input type="file" class="form-control @error('document_dashboard') is-invalid @enderror"
                            id="document_dashboard" name="document_dashboard" required accept=".pdf,.csv,.xlsx,.xls,.doc,.docx">
                        <small class="text-muted">Accepted formats: PDF, CSV, XLSX, XLS, DOC, DOCX (Max: 10MB)</small>
                        @error('document_dashboard')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Upload Proof of Identity Document -->
                    <div class="form-group">
                        <label for="document_identity" class="form-label">
                            Upload Proof of Identity Document <span class="required-mark">*</span>
                        </label>
                        <input type="file" class="form-control @error('document_identity') is-invalid @enderror"
                            id="document_identity" name="document_identity" required accept=".pdf,.csv,.xlsx,.xls,.doc,.docx">
                        <small class="text-muted">Accepted formats: PDF, CSV, XLSX, XLS, DOC, DOCX (Max: 10MB)</small>
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
                            <label class="radio-card" id="existingUserCard">
                                <input type="radio" name="payment_type" value="existing_user" id="existing_user"
                                    {{ old('payment_type') == 'existing_user' ? 'checked' : '' }} required>
                                <label for="existing_user" style="cursor: pointer; margin: 0;">
                                    <i class="fas fa-user-check"></i> Existing User
                                </label>
                            </label>
                            <label class="radio-card" id="partialUserCard">
                                <input type="radio" name="payment_type" value="partial_user" id="partial_user"
                                    {{ old('payment_type') == 'partial_user' ? 'checked' : '' }} required>
                                <label for="partial_user" style="cursor: pointer; margin: 0;">
                                    <i class="fas fa-money-check-alt"></i> Partial User
                                </label>
                            </label>
                            <label class="radio-card" id="newUserCard">
                                <input type="radio" name="payment_type" value="new_user" id="new_user"
                                    {{ old('payment_type') == 'new_user' ? 'checked' : '' }} required>
                                <label for="new_user" style="cursor: pointer; margin: 0;">
                                    <i class="fas fa-user-plus"></i> New User
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
                        <input type="file" class="form-control @error('document_payment_receipt') is-invalid @enderror"
                            id="document_payment_receipt" name="document_payment_receipt" accept=".pdf,.csv,.xlsx,.xls,.doc,.docx">
                        <small class="text-muted">Accepted formats: PDF, CSV, XLSX, XLS, DOC, DOCX (Max: 10MB)</small>
                        @error('document_payment_receipt')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Agreements -->
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input @error('terms_agreed') is-invalid @enderror"
                                id="terms_agreed" name="terms_agreed" value="1"
                                {{ old('terms_agreed') ? 'checked' : '' }} required>
                            <label class="form-check-label" style="margin-left: 10px" for="terms_agreed">
                                I agree to the
                                <a href="https://holaconnect.ie/user/register.php/HOLATaxiIrelandLimitedShareCertificateIssuanceAgreement.pdf"
                                    target="_blank">
                                    Hola Taxi Ireland Limited Shareholders Agreement
                                </a> <span class="required-mark">*</span>
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
                                id="share_certificate_agreed" name="share_certificate_agreed" value="1"
                                {{ old('share_certificate_agreed') ? 'checked' : '' }} required>
                            <label class="form-check-label" style="margin-left: 10px" for="share_certificate_agreed">
                                I agree to the
                                <a href="https://holaconnect.ie/user/register.php/HOLATaxiIrelandLimitedShareholdersAgreement.doc"
                                    target="_blank">
                                    Share Certificate Issue Agreement
                                </a> <span class="required-mark">*</span>
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

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">
                        <i class="fas fa-credit-card"></i> Complete Payment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closePaymentModal"></button>
                </div>
                <div class="modal-body">
                    <div id="paymentAmountSection">
                        <div class="form-group mb-3">
                            <label for="paymentAmount" class="form-label">
                                Payment Amount (EUR) <span class="required-mark">*</span>
                            </label>
                            <input type="text" class="form-control" id="paymentAmount" 
                                value="{{ \App\Models\Setting::get('registration_payment_amount') }}" 
                                readonly style="background-color: #f8f9fa; cursor: not-allowed;">
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn-primary" id="proceedToPaymentBtn">
                                <i class="fas fa-lock"></i> Proceed to Payment
                            </button>
                        </div>
                    </div>
                    <div id="paymentIframeSection" style="display: none;">
                        <div class="text-center mb-3">
                            <div class="spinner-border text-primary" role="status">
                            </div>
                            <p class="mt-2">Redirecting to secure payment gateway...</p>
                        </div>
                        <iframe id="paymentIframe" src="" style="width: 100%; height: 600px; border: none; display: none;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modal-content {
            border-radius: 10px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom: none;
        }
        .modal-header .btn-close {
            filter: invert(1);
        }
        #paymentIframe {
            border-radius: 8px;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const existingUserRadio = document.getElementById('existing_user');
            const partialUserRadio = document.getElementById('partial_user');
            const newUserRadio = document.getElementById('new_user');

            const paymentReceiptField = document.getElementById('paymentReceiptField');
            const paymentReceiptInput = document.getElementById('document_payment_receipt');
            
            const existingUserCard = document.getElementById('existingUserCard');
            const partialUserCard = document.getElementById('partialUserCard');
            const newUserCard = document.getElementById('newUserCard');

            const registrationForm = document.getElementById('registrationForm');
            const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            const paymentOrderCodeInput = document.getElementById('payment_order_code');
            const paymentAmountInput = document.getElementById('paymentAmount');
            const baseAmount = parseFloat("{{ \App\Models\Setting::get('registration_payment_amount') }}") || 0;
            let currentOrderCode = null;

            function togglePaymentReceipt() {
                // Reset active classes
                existingUserCard.classList.remove('active');
                partialUserCard.classList.remove('active');
                newUserCard.classList.remove('active');

                if (existingUserRadio.checked) {
                    // Existing User: Upload File + NO Payment
                    paymentReceiptField.style.display = 'block';
                    paymentReceiptInput.required = true;
                    existingUserCard.classList.add('active');
                } else if (partialUserRadio.checked) {
                    // Partial User: Upload File + Payment (Amount - 5)
                    paymentReceiptField.style.display = 'block';
                    paymentReceiptInput.required = true;
                    partialUserCard.classList.add('active');
                } else if (newUserRadio.checked) {
                    // New User: No Upload + Full Payment
                    paymentReceiptField.style.display = 'none';
                    paymentReceiptInput.required = false;
                    paymentReceiptInput.value = '';
                    newUserCard.classList.add('active');
                }
            }

            // Add event listeners
            existingUserRadio.addEventListener('change', togglePaymentReceipt);
            partialUserRadio.addEventListener('change', togglePaymentReceipt);
            newUserRadio.addEventListener('change', togglePaymentReceipt);

            // Initialize on page load
            togglePaymentReceipt();

            // Make radio cards clickable
            existingUserCard.addEventListener('click', function() {
                existingUserRadio.checked = true;
                togglePaymentReceipt();
            });

            partialUserCard.addEventListener('click', function() {
                partialUserRadio.checked = true;
                togglePaymentReceipt();
            });

            newUserCard.addEventListener('click', function() {
                newUserRadio.checked = true;
                togglePaymentReceipt();
            });

            // Intercept form submission
            registrationForm.addEventListener('submit', function(e) {
                // Case 1: Existing User - Submit Directly (No Payment)
                if (existingUserRadio.checked) {
                    return; // Allow default submit
                }

                // Case 2: Partial or New User payment needed
                if (partialUserRadio.checked || newUserRadio.checked) {
                    // Check if we already have a successful payment code
                    if (paymentOrderCodeInput.value) {
                         return; // Allow submit
                    }

                    // Prevent default submit to show payment modal
                    e.preventDefault();
                    
                    // Validate form first using browser built-in validation
                    if (!registrationForm.checkValidity()) {
                        registrationForm.reportValidity();
                        return;
                    }

                    // Set Payment Amount
                    if (newUserRadio.checked) {
                         paymentAmountInput.value = baseAmount.toFixed(2);
                    } else if (partialUserRadio.checked) {
                         let discountedAmount = baseAmount - 5;
                         if (discountedAmount < 0.30) discountedAmount = 0.30; 
                         paymentAmountInput.value = discountedAmount.toFixed(2);
                    }

                    // Show payment modal
                    paymentModal.show();
                }
            });

            // Handle proceed to payment button
            document.getElementById('proceedToPaymentBtn').addEventListener('click', function() {
                const amount = parseFloat(document.getElementById('paymentAmount').value);
                
                if (!amount || amount < 0.30) {
                    alert('Please enter a valid amount (minimum €0.30)');
                    return;
                }

                // Get form data
                const formData = new FormData(registrationForm);
                const email = formData.get('email');
                const name = formData.get('name');
                const phone = formData.get('phone');

                // Show loading
                document.getElementById('paymentAmountSection').style.display = 'none';
                document.getElementById('paymentIframeSection').style.display = 'block';

                // Create payment order
                fetch('{{ route("payment.create-order") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        amount: amount,
                        email: email,
                        name: name,
                        phone: phone
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentOrderCode = data.orderCode;
                        paymentOrderCodeInput.value = data.orderCode;
                        
                        // Load payment iframe
                        const iframe = document.getElementById('paymentIframe');
                        iframe.src = data.checkoutUrl;
                        iframe.style.display = 'block';
                        
                        // Listen for payment completion
                        startPaymentStatusCheck(data.orderCode);
                    } else {
                        alert('Failed to create payment order: ' + (data.message || 'Unknown error'));
                        document.getElementById('paymentAmountSection').style.display = 'block';
                        document.getElementById('paymentIframeSection').style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    document.getElementById('paymentAmountSection').style.display = 'block';
                    document.getElementById('paymentIframeSection').style.display = 'none';
                });
            });

            // Check payment status periodically
            function startPaymentStatusCheck(orderCode) {
                const checkInterval = setInterval(function() {
                    fetch('{{ route("payment.check-status") }}?orderCode=' + orderCode, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'completed') {
                            clearInterval(checkInterval);
                            finishRegistration(orderCode);
                        } else if (data.status === 'failed') {
                            clearInterval(checkInterval);
                            alert('Payment failed. Please try again.');
                            paymentModal.hide();
                        }
                    })
                    .catch(error => {
                        console.error('Error checking payment status:', error);
                    });
                }, 3000); // Check every 3 seconds

                // Stop checking after 10 minutes
                setTimeout(function() {
                    clearInterval(checkInterval);
                }, 600000);
            }

            // Listen for postMessage from iframe
            window.addEventListener('message', function(event) {
                if (event.data && (event.data.type === 'payment_success' || event.data.type === 'payment_completed')) {
                    if (currentOrderCode) {
                        verifyAndFinish(currentOrderCode);
                    }
                } else if (event.data && event.data.type === 'payment_failed') {
                    alert('Payment failed. Please try again.');
                    paymentModal.hide();
                }
            });

            function verifyAndFinish(orderCode) {
                fetch('{{ route("payment.check-status") }}?orderCode=' + orderCode, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'completed') {
                        finishRegistration(orderCode);
                    }
                })
                .catch(error => {
                    console.error('Error verifying payment:', error);
                });
            }

            function finishRegistration(orderCode) {
                paymentOrderCodeInput.value = orderCode;
                paymentModal.hide();
                // Ensure submit happens after modal hides
                setTimeout(() => {
                    registrationForm.submit();
                }, 500);
            }

            // Reset modal when closed
            document.getElementById('paymentModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('paymentAmountSection').style.display = 'block';
                document.getElementById('paymentIframeSection').style.display = 'none';
                document.getElementById('paymentIframe').src = '';
                document.getElementById('paymentIframe').style.display = 'none';
                currentOrderCode = null;
            });
        });
    </script>
@endpush
