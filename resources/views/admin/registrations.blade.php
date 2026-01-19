@extends('layouts.app')

@section('title', 'All Registrations - Hola Taxi Ireland')

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
        <div class="card">
            <div class="card-header">{{ $title }}</div>
            <div class="card-body">
                <div class="row mb-3 g-2 align-items-end">
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
    </script>
@endpush
