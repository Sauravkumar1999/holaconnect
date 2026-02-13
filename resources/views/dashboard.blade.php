@extends('layouts.app')

@section('title', 'Dashboard - Hola Taxi')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="user-info">
        <h3><i class="fas fa-user-circle"></i> Welcome, {{ Auth::user()->user_type == 1 ? Auth::user()->name : 'Admin' }}!</h3>
        @if (Auth::user()->user_type == 1)
            @switch (Auth::user()->application_status)
                @case('pending')
                    <p>Your registration has been submitted successfully. Our team will review your application.</p>
                    @break
                @case('accepted')
                    <p>Your registration has been accepted. You can now access your account.</p>
                    @break
                @case('rejected')
                    <p>Your registration has been rejected. Please contact support for more information.</p>
                    @break
                @default
            @endswitch
        @else
            <p>You are logged in as an admin.</p>
        @endif
    </div>
    @if (Auth::user()->user_type == 1)
        {{-- Application Status Card --}}
        @php
            $status = Auth::user()->application_status ?? 'pending';
        @endphp
        <div class="card" style="border-left: 4px solid 
            @if($status === 'accepted') #10b981
            @elseif($status === 'rejected') #ef4444
            @else #f59e0b
            @endif;">
            <h2>
                <i class="fas 
                    @if($status === 'accepted') fa-check-circle
                    @elseif($status === 'rejected') fa-times-circle
                    @else fa-clock
                    @endif"></i> 
                Application Status
            </h2>

            @if($status === 'pending')
                <div style="background: #fef3c7; padding: 1.5rem; border-radius: 8px; border: 1px solid #fbbf24;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <i class="fas fa-hourglass-half" style="font-size: 2rem; color: #f59e0b;"></i>
                        <div>
                            <h3 style="margin: 0; color: #92400e; font-size: 1.25rem;">Application Pending Review</h3>
                            <p style="margin: 0.5rem 0 0 0; color: #78350f;">Your application is currently under review by our team. We will notify you once a decision has been made.</p>
                        </div>
                    </div>
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #fbbf24;">
                        <small style="color: #78350f;">
                            <i class="fas fa-info-circle"></i> 
                            Submitted on {{ Auth::user()->created_at->format('d M Y, h:i A') }}
                        </small>
                    </div>
                </div>
            @elseif($status === 'accepted')
                <div style="background: #d1fae5; padding: 1.5rem; border-radius: 8px; border: 1px solid #10b981;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <i class="fas fa-check-circle" style="font-size: 2rem; color: #10b981;"></i>
                        <div>
                            <h3 style="margin: 0; color: #065f46; font-size: 1.25rem;">Application Accepted!</h3>
                            <p style="margin: 0.5rem 0 0 0; color: #064e3b;">Congratulations! Your application has been approved.</p>
                        </div>
                    </div>
                    
                    @if(Auth::user()->certificate_path)
                        <div style="margin-top: 1rem; padding: 1rem; background: white; border-radius: 6px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                                <div>
                                    <h4 style="margin: 0 0 0.5rem 0; color: #065f46;">
                                        <i class="fas fa-certificate"></i> Share Certificate
                                    </h4>
                                    <p style="margin: 0; font-size: 0.875rem; color: #059669;">
                                        <strong>Certificate No:</strong> {{ Auth::user()->certificate_number }}<br>
                                        <strong>Issued Date:</strong> {{ Auth::user()->certificate_issued_date ? Auth::user()->certificate_issued_date->format('d M Y') : 'N/A' }}
                                    </p>
                                </div>
                                <a href="{{ asset(Auth::user()->certificate_path) }}" 
                                   target="_blank" 
                                   download
                                   class="btn btn-success">
                                    <i class="fas fa-download"></i> Download Certificate
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            @elseif($status === 'rejected')
                <div style="background: #fee2e2; padding: 1.5rem; border-radius: 8px; border: 1px solid #ef4444;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <i class="fas fa-times-circle" style="font-size: 2rem; color: #ef4444;"></i>
                        <div>
                            <h3 style="margin: 0; color: #7f1d1d; font-size: 1.25rem;">Application Rejected</h3>
                            <p style="margin: 0.5rem 0 0 0; color: #991b1b;">Unfortunately, your application has been rejected. Please contact support for more information or to reapply.</p>
                        </div>
                    </div>
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #ef4444;">
                        <a href="mailto:support@holataxiireland.com" style="color: #991b1b; text-decoration: underline;">
                            <i class="fas fa-envelope"></i> Contact Support
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <div class="card">
            <h2><i class="fas fa-info-circle"></i> Your Registration Details</h2>

            <div class="info-grid">
                <div class="info-item">
                    <label>Full Name</label>
                    <p>{{ Auth::user()->name }}</p>
                </div>

                <div class="info-item">
                    <label>Email Address</label>
                    <p>{{ Auth::user()->email }}</p>
                </div>

                <div class="info-item">
                    <label>Phone Number</label>
                    <p>{{ Auth::user()->phone }}</p>
                </div>

                @if (Auth::user()->psp_number)
                    <div class="info-item">
                        <label>PSP Number</label>
                        <p>{{ Auth::user()->psp_number }}</p>
                    </div>
                @endif

                @if (Auth::user()->taxi_driver_id)
                    <div class="info-item">
                        <label>Taxi Driver ID</label>
                        <p>{{ Auth::user()->taxi_driver_id }}</p>
                    </div>
                @endif

                <div class="info-item">
                    <label>Payment Type</label>
                    <p>{{ ucfirst(str_replace('_', ' ', Auth::user()->payment_type)) }}</p>
                </div>

                <div class="info-item">
                    <label>User Type</label>
                    <p>{{ Auth::user()->user_type == 0 ? 'Admin' : 'User' }}</p>
                </div>

                <div class="info-item">
                    <label>Registration Date</label>
                    <p>{{ Auth::user()->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        </div>
        <div class="card">
            <h2><i class="fas fa-file-upload"></i> Uploaded Documents</h2>

            <div class="info-grid">
                <div class="info-item">
                    <label>Dashboard Document</label>
                    <p>
                        @if (Auth::user()->document_dashboard_path)
                            <a href="{{ asset(Auth::user()->document_dashboard_path) }}" target="_blank"
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
                        @if (Auth::user()->document_identity_path)
                            <a href="{{ asset(Auth::user()->document_identity_path) }}" target="_blank"
                                style="color: #667eea;">
                                <i class="fas fa-download"></i> View Document
                            </a>
                        @else
                            <span style="color: #94a3b8;">Not uploaded</span>
                        @endif
                    </p>
                </div>

                @if (Auth::user()->payment_type === 'pre_payment' && Auth::user()->document_payment_receipt_path)
                    <div class="info-item">
                        <label>Payment Receipt</label>
                        <p>
                            <a href="{{ asset(Auth::user()->document_payment_receipt_path) }}" target="_blank"
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
    @endif


@endsection
