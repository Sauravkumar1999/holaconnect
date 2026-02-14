<?php

namespace App\Http\Controllers;

use App\Mail\ApplicationAccepted;
use App\Models\User;
use App\Services\CertificateGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class AdminController extends Controller
{

    public function __construct()
    {
        if (Auth::user()->user_type != "0") {
            abort(403, 'Unauthorized access.');
        }
    }
    /**
     * Show all registrations.
     */
    public function registrations(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $query = User::where('user_type', 1);

            // Apply date range filter if provided
            if ($request->has('daterange') && $request->get('daterange')) {
                $dates = explode(' - ', $request->get('daterange'));
                if (count($dates) == 2) {
                    try {
                        $start = \Carbon\Carbon::parse($dates[0])->startOfDay();
                        $end = \Carbon\Carbon::parse($dates[1])->endOfDay();
                        $query->whereBetween('created_at', [$start, $end]);
                    } catch (\Exception $e) {
                        // Invalid date format, ignore filter
                    }
                }
            }

            return DataTables::eloquent($query)
                ->addColumn('application_status', function ($user) {
                    // Treat null as pending
                    $status = $user->application_status ?? 'pending';
                    $statusBadges = [
                        'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
                        'accepted' => '<span class="badge bg-success">Accepted</span>',
                        'rejected' => '<span class="badge bg-danger">Rejected</span>',
                    ];
                    return $statusBadges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
                })
                ->addColumn('action', function ($user) {
                    $dropdown = '
                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="actionDropdown' . $user->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog"></i> Actions
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="actionDropdown' . $user->id . '">
                            <li>
                                <a class="dropdown-item" href="' . route('registration.details', $user) . '">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </li>';

                    // Treat null as pending
                    if ($user->application_status == 'pending' || $user->application_status === null) {
                        $dropdown .= '
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-success accept-application" href="javascript:void(0)" data-user-id="' . $user->id . '">
                                    <i class="fas fa-check-circle"></i> Accept
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger reject-application" href="javascript:void(0)" data-user-id="' . $user->id . '">
                                    <i class="fas fa-times-circle"></i> Reject
                                </a>
                            </li>';
                    } elseif ($user->application_status == 'accepted') {
                        $dropdown .= '
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-warning reaccept-application" href="javascript:void(0)" data-user-id="' . $user->id . '">
                                    <i class="fas fa-redo"></i> Re-accept
                                </a>
                            </li>';
                    }

                    $dropdown .= '
                        </ul>
                    </div>';

                    return $dropdown;
                })
                ->addColumn('document_dashboard', function ($user) {
                    if ($user->document_dashboard_path) {
                        return '<a href="' . asset($user->document_dashboard_path) . '" target="_blank" class="text-primary" title="View Dashboard Document">
                            <i class="fas fa-file-alt"></i>
                        </a>';
                    }
                    return '<span class="text-muted"><i class="fas fa-times"></i></span>';
                })
                ->addColumn('document_identity', function ($user) {
                    if ($user->document_identity_path) {
                        return '<a href="' . asset($user->document_identity_path) . '" target="_blank" class="text-primary" title="View Identity Document">
                            <i class="fas fa-id-card"></i>
                        </a>';
                    }
                    return '<span class="text-muted"><i class="fas fa-times"></i></span>';
                })
                ->addColumn('document_receipt', function ($user) {
                    if ($user->document_payment_receipt_path) {
                        return '<a href="' . asset($user->document_payment_receipt_path) . '" target="_blank" class="text-primary" title="View Payment Receipt">
                            <i class="fas fa-receipt"></i>
                        </a>';
                    }
                    return '<span class="text-muted"><i class="fas fa-times"></i></span>';
                })
                ->addColumn('certificate', function ($user) {
                    if ($user->certificate_path && $user->application_status === 'accepted') {
                        return '<a href="' . asset($user->certificate_path) . '" download class="text-primary" title="Download Certificate">
                            <i class="fas fa-download"></i>
                        </a>';
                    }
                    return '<span class="text-muted"><i class="fas fa-times"></i></span>';
                })
                ->editColumn('payment_type', function ($user) {
                    $badgeClass = $user->payment_type === 'pre_payment' ? 'bg-primary' : 'bg-success';
                    return '<span class="badge ' . $badgeClass . '">' . ucfirst(str_replace('_', ' ', $user->payment_type)) . '</span>';
                })
                ->editColumn('psp_number', function ($user) {
                    return $user->psp_number ?? '-';
                })
                ->editColumn('taxi_driver_id', function ($user) {
                    return $user->taxi_driver_id ?? '-';
                })
                ->editColumn('created_at', function ($user) {
                    return $user->created_at->format('d M Y, h:i A');
                })
                ->rawColumns(['action', 'document_dashboard', 'document_identity', 'document_receipt', 'certificate', 'payment_type', 'application_status'])
                ->make(true);
        }

        $html = $htmlBuilder
            ->setTableId('registrations-table')
            ->columns([
                Column::make('id')->title('ID'),
                Column::make('name')->title('Name'),
                Column::make('email')->title('Email'),
                Column::make('phone')->title('Phone'),
                Column::make('psp_number')->title('PSP Number'),
                Column::make('taxi_driver_id')->title('Taxi Driver ID'),
                Column::make('payment_type')->title('Payment Type'),
                Column::make('document_dashboard')->title('<i class="fas fa-file-alt"></i> Dashboard')->orderable(false)->searchable(false)->addClass('text-center'),
                Column::make('document_identity')->title('<i class="fas fa-id-card"></i> Identity')->orderable(false)->searchable(false)->addClass('text-center'),
                Column::make('document_receipt')->title('<i class="fas fa-receipt"></i> Receipt')->orderable(false)->searchable(false)->addClass('text-center'),
                Column::make('application_status')->title('Status'),
                Column::computed('certificate')->title('<i class="fas fa-certificate"></i> Certificate')->orderable(false)->searchable(false)->addClass('text-center')->exportable(false)->printable(false),
                Column::make('created_at')->title('Registered At'),
                Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->width(100)
                    ->addClass('text-center')
                    ->title('Actions'),
            ])
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->buttons(
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('colvis')
            )
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                'pageLength' => 25,
                'order' => [[0, 'desc']],
            ]);

        return view('admin.registrations', [
            'title' => 'All Registrations',
            'dataTable' => $html
        ]);
    }

    public function registrationDetails(User $user)
    {
        return view('admin.registration-details', compact('user'));
    }

    /**
     * Accept a user's application and generate certificate.
     */
    public function acceptApplication(Request $request, User $user, CertificateGenerationService $certificateService)
    {
        // Treat null as pending - only allow accept if pending or null
        if ($user->application_status !== 'pending' && $user->application_status !== null) {
            return response()->json([
                'success' => false,
                'message' => 'This application has already been processed.'
            ], 400);
        }

        try {
            $shares = is_null($user->payment) ? 50000 : 12000;
            // Generate certificate number
            $certificateNumber = $certificateService->generateCertificateNumber($user->id);

            // Generate certificate date
            $issuedDate = now()->format('d M Y');

            // Generate certificate image
            $certificatePath = $certificateService->generateCertificate(
                $user->name,
                $certificateNumber,
                $issuedDate,
                $user->taxi_driver_id,
                $shares
            );

            // Update user record
            $user->update([
                'application_status' => 'accepted',
                'certificate_path' => $certificatePath,
                'certificate_number' => $certificateNumber,
                'certificate_issued_date' => now(),
            ]);

            // Send acceptance email to user
            Mail::to($user->email)->send(new ApplicationAccepted($user));

            return response()->json([
                'success' => true,
                'message' => 'Application accepted successfully and certificate generated.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to accept application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a user's application.
     */
    public function rejectApplication(Request $request, User $user)
    {
        // Treat null as pending - only allow reject if pending or null
        if ($user->application_status !== 'pending' && $user->application_status !== null) {
            return response()->json([
                'success' => false,
                'message' => 'This application has already been processed.'
            ], 400);
        }

        try {
            $user->update([
                'application_status' => 'rejected',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application rejected successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Re-accept a user's application and regenerate certificate.
     */
    public function reacceptApplication(Request $request, User $user, CertificateGenerationService $certificateService)
    {
        // Only allow re-accept if already accepted
        if ($user->application_status !== 'accepted') {
            return response()->json([
                'success' => false,
                'message' => 'This application must be in accepted status to re-accept.'
            ], 400);
        }

        try {
            // Delete old certificate file if it exists
            $shares = is_null($user->payment) ? 50000 : 12000;
            if ($user->certificate_path) {
                $oldCertificatePath = public_path($user->certificate_path);
                if (file_exists($oldCertificatePath)) {
                    unlink($oldCertificatePath);
                }
            }

            // Generate new certificate number
            $certificateNumber = $certificateService->generateCertificateNumber($user->id);

            // Generate certificate date
            $issuedDate = now()->format('d M Y');

            // Generate new certificate image
            $certificatePath = $certificateService->generateCertificate(
                $user->name,
                $certificateNumber,
                $issuedDate,
                $user->taxi_driver_id,
                $shares
            );

            // Update user record with new certificate
            $user->update([
                'certificate_path' => $certificatePath,
                'certificate_number' => $certificateNumber,
                'certificate_issued_date' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application re-accepted successfully and new certificate generated.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to re-accept application: ' . $e->getMessage()
            ], 500);
        }
    }
}
