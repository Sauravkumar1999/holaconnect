<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class AdminController extends Controller
{
    /**
     * Show all registrations.
     */
    public function registrations(Request $request, Builder $htmlBuilder)
    {
        // Check if user is admin
        if (Auth::user()->user_type !== 0) {
            abort(403, 'Unauthorized access.');
        }

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
                ->addColumn('action', function ($user) {
                    return '<a href="' . route('dashboard') . '?user_id=' . $user->id . '" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> View
                    </a>';
                })
                ->addColumn('documents', function ($user) {
                    $documents = [];
                    if ($user->document_dashboard_path) {
                        $documents[] = '<a href="' . asset($user->document_dashboard_path) . '" target="_blank" class="text-primary me-2">
                            <i class="fas fa-file-alt"></i> Dashboard
                        </a>';
                    }
                    if ($user->document_identity_path) {
                        $documents[] = '<a href="' . asset($user->document_identity_path) . '" target="_blank" class="text-primary me-2">
                            <i class="fas fa-id-card"></i> Identity
                        </a>';
                    }
                    if ($user->document_payment_receipt_path) {
                        $documents[] = '<a href="' . asset($user->document_payment_receipt_path) . '" target="_blank" class="text-primary me-2">
                            <i class="fas fa-receipt"></i> Receipt
                        </a>';
                    }
                    return !empty($documents) ? implode('', $documents) : '<span class="text-muted">No documents</span>';
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
                ->rawColumns(['action', 'documents', 'payment_type'])
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
                Column::make('documents')->title('Documents')->orderable(false)->searchable(false),
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
}
