@extends('layouts.app')

@section('title', 'All Registrations - Hola Taxi')

@section('page-title')
    <i class="fas fa-users"></i> All Registrations
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.5/css/buttons.bootstrap5.min.css">
    <link href="https://cdn.datatables.net/v/dt/dt-2.3.5/b-3.2.5/b-colvis-3.2.5/datatables.min.css" rel="stylesheet"
        integrity="sha384-gCgh7e0dCj9UjbRDAftkhzrjwYqzzh/KU7ZhaNGU9c63mVinPdBK0lXYJO3PGKHQ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/colreorder/2.1.2/css/colReorder.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.7/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.8.4/css/searchBuilder.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/3.1.3/css/select.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@section('content')
    <div class="container-fluid">
        <div class="card px-0">
            <div class="card-body px-0">
                <div class="row px-2 mb-3 g-2 align-items-end">
                    <!-- Date Range -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Date Range</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa-regular fa-calendar-days"></i>
                            </span>
                            <input type="text" name="daterange" class="form-control" placeholder="Select date range"
                                id="date-range">
                        </div>
                    </div>

                    <!-- Custom Filters Slot -->
                    @if (isset($filters))
                        {{ $filters }}
                    @endif
                </div>

                {!! $dataTable->table(attributes: ['class' => 'table table-striped table-hover']) !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.5/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.5/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.5/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/colreorder/2.1.2/js/dataTables.colReorder.min.js"></script>
    <script src="https://cdn.datatables.net/colreorder/2.1.2/js/colReorder.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.8.4/js/dataTables.searchBuilder.min.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.8.4/js/searchBuilder.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/select/3.1.3/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/select/3.1.3/js/select.bootstrap5.min.js"></script>
    {!! $dataTable->scripts() !!}

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize date range picker
            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#date-range').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $('#date-range').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

            // Wait for DataTable to initialize, then override ajax data
            setTimeout(function() {
                if (window.LaravelDataTables && window.LaravelDataTables['registrations-table']) {
                    var table = window.LaravelDataTables['registrations-table'];
                    var settings = table.settings()[0];

                    // Override ajax data to include date range
                    if (settings.ajax && typeof settings.ajax === 'object') {
                        var originalData = settings.ajax.data;
                        settings.ajax.data = function(d) {
                            d.daterange = $('#date-range').val() || '';
                            if (originalData && typeof originalData === 'function') {
                                originalData.call(this, d);
                            }
                        };
                    }
                }
            }, 100);

            // Date range filter change handler
            $('#date-range').on('apply.daterangepicker', function(ev, picker) {
                if (window.LaravelDataTables && window.LaravelDataTables['registrations-table']) {
                    window.LaravelDataTables['registrations-table'].draw();
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '.dt-delete-btn', function() {
            let url = $(this).data('action-url');

            if (typeof sweetalert !== 'undefined') {
                sweetalert('Delete?', 'Delete Selected Data.', 'warning', 'Delete')
                    .then(result => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: url,
                                type: "DELETE",
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(res) {
                                    // Reload all DataTables on the page
                                    $('.dataTable').each(function() {
                                        $(this).DataTable().ajax.reload(null, false);
                                    });

                                    if (typeof showSweetToast !== 'undefined') {
                                        showSweetToast(res.message, 'success');
                                    } else if (typeof showToast !== 'undefined') {
                                        showToast(res.message, 'success');
                                    }
                                },
                                error: function() {
                                    if (typeof showToast !== 'undefined') {
                                        showToast('Delete failed', 'error');
                                    }
                                }
                            });
                        }
                    });
            }
        });

        // Accept application handler
        $(document).on('click', '.accept-application', function() {
            let userId = $(this).data('user-id');
            let url = '/registrations/' + userId + '/accept';

            Swal.fire({
                title: 'Accept Application?',
                text: 'This will generate and issue a share certificate to the user.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Accept',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Generating certificate, please wait...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: res.message,
                                timer: 3000,
                                showConfirmButton: false
                            });

                            // Reload DataTable
                            if (window.LaravelDataTables && window.LaravelDataTables['registrations-table']) {
                                window.LaravelDataTables['registrations-table'].ajax.reload(null, false);
                            }
                        },
                        error: function(xhr) {
                            let message = 'Failed to accept application';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message
                            });
                        }
                    });
                }
            });
        });

        // Reject application handler
        $(document).on('click', '.reject-application', function() {
            let userId = $(this).data('user-id');
            let url = '/registrations/' + userId + '/reject';

            Swal.fire({
                title: 'Reject Application?',
                text: 'Are you sure you want to reject this application? This action will notify the user.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Reject',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Rejected',
                                text: res.message,
                                timer: 3000,
                                showConfirmButton: false
                            });

                            // Reload DataTable
                            if (window.LaravelDataTables && window.LaravelDataTables['registrations-table']) {
                                window.LaravelDataTables['registrations-table'].ajax.reload(null, false);
                            }
                        },
                        error: function(xhr) {
                            let message = 'Failed to reject application';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message
                            });
                        }
                    });
                }
            });
        });

        // Re-accept application handler
        $(document).on('click', '.reaccept-application', function() {
            let userId = $(this).data('user-id');
            let url = '/registrations/' + userId + '/reaccept';

            Swal.fire({
                title: 'Re-accept Application?',
                html: 'This will:<br><ul class="text-start"><li>Delete the old certificate</li><li>Generate a new certificate with a new number</li></ul>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Re-accept',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Deleting old certificate and generating new one, please wait...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: res.message,
                                timer: 3000,
                                showConfirmButton: false
                            });

                            // Reload DataTable
                            if (window.LaravelDataTables && window.LaravelDataTables['registrations-table']) {
                                window.LaravelDataTables['registrations-table'].ajax.reload(null, false);
                            }
                        },
                        error: function(xhr) {
                            let message = 'Failed to re-accept application';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
