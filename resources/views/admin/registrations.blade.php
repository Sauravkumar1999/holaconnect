@extends('layouts.app')

@section('title', 'All Registrations - Hola Taxi Ireland')

@section('page-title')
    <i class="fas fa-users"></i> All Registrations
@endsection

@push('styles')
<style>
    .registrations-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .registrations-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .registrations-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .registrations-table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .registrations-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .registrations-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .registrations-table tbody tr:last-child td {
        border-bottom: none;
    }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-pre-payment {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .badge-new-payment {
        background-color: #dcfce7;
        color: #166534;
    }

    .btn-view {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .document-link {
        color: #667eea;
        text-decoration: none;
        margin-right: 1rem;
        font-size: 0.875rem;
    }

    .document-link:hover {
        text-decoration: underline;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        text-align: center;
    }

    .stat-card h3 {
        margin: 0;
        font-size: 2rem;
        color: #667eea;
        font-weight: 700;
    }

    .stat-card p {
        margin: 0.5rem 0 0;
        color: #64748b;
        font-size: 0.875rem;
    }

    .no-data {
        text-align: center;
        padding: 3rem;
        color: #94a3b8;
    }

    .no-data i {
        font-size: 3rem;
        margin-bottom: 1rem;
        display: block;
    }
</style>
@endpush

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <h3>{{ $registrations->count() }}</h3>
            <p>Total Registrations</p>
        </div>
        <div class="stat-card">
            <h3>{{ $registrations->where('payment_type', 'pre_payment')->count() }}</h3>
            <p>Pre-Payment</p>
        </div>
        <div class="stat-card">
            <h3>{{ $registrations->where('payment_type', 'new_payment')->count() }}</h3>
            <p>New Payment</p>
        </div>
    </div>

    <div class="card">
        <h2><i class="fas fa-users"></i> All Registrations</h2>

        @if($registrations->count() > 0)
            <div style="overflow-x: auto;">
                <table class="registrations-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>PSP Number</th>
                            <th>Taxi Driver ID</th>
                            <th>Payment Type</th>
                            <th>Documents</th>
                            <th>Registered At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $registration)
                            <tr>
                                <td>{{ $registration->id }}</td>
                                <td><strong>{{ $registration->name }}</strong></td>
                                <td>{{ $registration->email }}</td>
                                <td>{{ $registration->phone }}</td>
                                <td>{{ $registration->psp_number ?? '-' }}</td>
                                <td>{{ $registration->taxi_driver_id ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $registration->payment_type === 'pre_payment' ? 'pre' : 'new' }}-payment">
                                        {{ ucfirst(str_replace('_', ' ', $registration->payment_type)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($registration->document_dashboard_path)
                                        <a href="{{ asset($registration->document_dashboard_path) }}" target="_blank" class="document-link">
                                            <i class="fas fa-file-alt"></i> Dashboard
                                        </a>
                                    @endif
                                    @if($registration->document_identity_path)
                                        <a href="{{ asset($registration->document_identity_path) }}" target="_blank" class="document-link">
                                            <i class="fas fa-id-card"></i> Identity
                                        </a>
                                    @endif
                                    @if($registration->document_payment_receipt_path)
                                        <a href="{{ asset($registration->document_payment_receipt_path) }}" target="_blank" class="document-link">
                                            <i class="fas fa-receipt"></i> Receipt
                                        </a>
                                    @endif
                                    @if(!$registration->document_dashboard_path && !$registration->document_identity_path && !$registration->document_payment_receipt_path)
                                        <span style="color: #94a3b8;">No documents</span>
                                    @endif
                                </td>
                                <td>{{ $registration->created_at->format('d M Y, h:i A') }}</td>
                                <td>
                                    <a href="{{ route('dashboard') }}?user_id={{ $registration->id }}" class="btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="no-data">
                <i class="fas fa-inbox"></i>
                <p>No registrations found.</p>
            </div>
        @endif
    </div>
@endsection
