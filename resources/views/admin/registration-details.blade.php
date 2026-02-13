@extends('layouts.app')

@section('title', 'Registration Details - Hola Taxi')

@section('page-title')
    <i class="fas fa-user-circle"></i> Registration Details
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <h2><i class="fas fa-info-circle"></i> {{ $user->name }} Registration Details</h2>

            <div class="info-grid">
                <div class="info-item">
                    <label>Full Name</label>
                    <p>{{ $user->name }}</p>
                </div>

                <div class="info-item">
                    <label>Email Address</label>
                    <p>{{ $user->email }}</p>
                </div>

                <div class="info-item">
                    <label>Phone Number</label>
                    <p>{{ $user->phone }}</p>
                </div>

                @if ($user->psp_number)
                    <div class="info-item">
                        <label>PSP Number</label>
                        <p>{{ $user->psp_number }}</p>
                    </div>
                @endif

                @if ($user->taxi_driver_id)
                    <div class="info-item">
                        <label>Taxi Driver ID</label>
                        <p>{{ $user->taxi_driver_id }}</p>
                    </div>
                @endif

                <div class="info-item">
                    <label>Payment Type</label>
                    <p>{{ ucfirst(str_replace('_', ' ', $user->payment_type)) }}</p>
                </div>

                <div class="info-item">
                    <label>User Type</label>
                    <p>{{ $user->user_type == 0 ? 'Admin' : 'User' }}</p>
                </div>

                <div class="info-item">
                    <label>Registration Date</label>
                    <p>{{ $user->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        </div>
        <div class="card">
            <h2><i class="fas fa-file-upload"></i> Uploaded Documents</h2>

            <div class="info-grid">
                <div class="info-item">
                    <label>Dashboard Document</label>
                    <p>
                        @if ($user->document_dashboard_path)
                            <a href="{{ asset($user->document_dashboard_path) }}" target="_blank"
                                style="color: #667eea;">
                                <i class="fas fa-download"></i> View Document
                            </a>
                        @else
                            <span style="color: #94a3b8;">Not uploaded</span>
                        @endif
                    </p>
                </div>

                <div class="info-item">
                    <label>Identity Document</label>
                    <p>
                        @if ($user->document_identity_path)
                            <a href="{{ asset($user->document_identity_path) }}" target="_blank"
                                style="color: #667eea;">
                                <i class="fas fa-download"></i> View Document
                            </a>
                        @else
                            <span style="color: #94a3b8;">Not uploaded</span>
                        @endif
                    </p>
                </div>

                @if ($user->payment_type === 'pre_payment' && $user->document_payment_receipt_path)
                    <div class="info-item">
                        <label>Payment Receipt</label>
                        <p>
                            <a href="{{ asset($user->document_payment_receipt_path) }}" target="_blank"
                                style="color: #667eea;">
                                <i class="fas fa-download"></i> View Document
                            </a>
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <h2><i class="fas fa-check-square"></i> Agreements</h2>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.25rem;"></i>
                    <span>Agreed to "Hola Taxi Ireland" terms and conditions</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.25rem;"></i>
                    <span>Agreed to "Share Certificate Issue"</span>
                </div>
            </div>
        </div>
    </div>
@endsection
