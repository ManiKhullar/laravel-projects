
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
	<title>View Title</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

	<link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
	<!-- Include CSS -->
	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<!-- <link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet"> -->
	<!-- <link href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet"> -->
	<!-- <link href="{{ asset('assets/css/fullcalendar.min.css') }}" rel="stylesheet"> -->
	<!-- <link href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"> -->
	<!-- <link href="{{ asset('assets/plugins/morris/morris.css') }}" rel="stylesheet"> -->
	<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/plugins/datetimepicker/css/tempusdominus-bootstrap-4.min.css') }}">
	<!-- custom css  -->
	<link rel="stylesheet" href="{{ asset('css/loader.css') }}">
    <link rel="stylesheet" href="{{ asset('css/live-search.css') }}">
	<link rel="stylesheet" href="{{ asset('css/lead_search_beforEntery.css') }}">
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
	<link rel="stylesheet" href="{{ asset('css/paginati_Laravel.css') }}">

	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

	<link href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

</head>
<body>
  
    @include('section.header')
    @include('section.sidebar')

<div class="page-wrapper">
    <div class="content container-fluid">
        
        <div id="custom-page-header">
            <div class="row mt-4">
                <div class="col-12">
                    <div class="lead-status-cards">
                        @php
                            $statusMapping = [
                                1 => ['badgeClass' => 'badge-primary', 'iconClass' => 'fas fa-search', 'cardClass' => 'bg-light-primary'],
                                2 => ['badgeClass' => 'badge-secondary', 'iconClass' => 'fas fa-phone', 'cardClass' => 'bg-light-secondary'],
                                3 => ['badgeClass' => 'badge-info', 'iconClass' => 'fas fa-cog', 'cardClass' => 'bg-light-info'],
                                4 => ['badgeClass' => 'badge-success', 'iconClass' => 'fas fa-file-alt', 'cardClass' => 'bg-light-success'],
                                5 => ['badgeClass' => 'badge-warning', 'iconClass' => 'fas fa-file-signature', 'cardClass' => 'bg-light-warning'],
                                6 => ['badgeClass' => 'badge-danger', 'iconClass' => 'fas fa-file-invoice', 'cardClass' => 'bg-light-danger'],
                                13 => ['badgeClass' => 'badge-danger', 'iconClass' => 'fas fa-times-circle', 'cardClass' => 'bg-light-danger'],
                                14 => ['badgeClass' => 'badge-dark', 'iconClass' => 'fas fa-users', 'cardClass' => 'bg-light-dark'],
                                15 => ['badgeClass' => 'badge-info', 'iconClass' => 'fas fa-check-circle', 'cardClass' => 'bg-light-success'] 
                            ];
                        @endphp

                        @foreach ($LeadStatuss as $status)
                            @php
                                $badgeClass = $statusMapping[$status->leadstatusid]['badgeClass'] ?? 'badge-secondary';
                                $iconClass = $statusMapping[$status->leadstatusid]['iconClass'] ?? 'fas fa-info-circle';
                                $cardClass = $statusMapping[$status->leadstatusid]['cardClass'] ?? 'bg-light-secondary';
                            @endphp
                            
                            <div class="lead-status-card text-center {{ $cardClass }}">
                            <a target="_blank" href="{{ route('leadstatus.filterRecord', ['id' => $status->leadstatusid]) }}" class="lead-status-card-link">

                                <div class="card-body">
                                    <span class="badge {{ $badgeClass }} mb-2">
                                        <i class="{{ $iconClass }}"></i>
                                    </span>
                                    <h6 class="card-title">{{ $status->leadstatusname }}</h6>
                                    <p class="card-text">{{ $leads->where('interview_status', $status->leadstatusid)->count() }}</p>
                                </div>
                                </a>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <!-- Display Toastr messages -->
        <script>
            $(document).ready(function() {
                @if(session('error'))
                    toastr.error("{{ session('error') }}", "", { 
                        timeOut: 5000, 
                        progressBar: true,
                        positionClass: "toast-top-center"
                    });
                @endif

                @if(session('success'))
                    toastr.success("{{ session('success') }}", "", { 
                        timeOut: 5000, 
                        progressBar: true,
                        positionClass: "toast-top-center"
                    });
                @endif
            });
        </script>
        
        <div class="row">
            <div class="col-lg-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="page-title">
                                  C2C  Lead Lists
                                </div>
                            </div>
                        </div>
                    </div>
                       <!-- Display flash messages -->
                        @if(session('message'))
                            <div class="alert alert-warning">
                                {{ session('message') }}
                            </div>
                        @endif
                        <!-- <form class="m-b-30" method="POST" action="{{ route('search.leads') }}" enctype="multipart/form-data" id="filterForm">
                            @csrf
                            <div class="row filter-row">
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group form-focus">
                                        <input class="form-control datetimepicker-input floating datetimepicker" type="text" name="from_date" data-toggle="datetimepicker" placeholder="DD-MM-YYYY" value="{{ old('from_date', isset($selectedValues['from_date']) ? $selectedValues['from_date'] : '') }}" autocomplete="off">
                                        <label class="focus-label">From</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group form-focus">
                                        <input class="form-control datetimepicker-input datetimepicker floating" type="text" name="to_date" data-toggle="datetimepicker" placeholder="DD-MM-YYYY" value="{{ old('to_date', isset($selectedValues['to_date']) ? $selectedValues['to_date'] : '') }}" autocomplete="off">
                                        <label class="focus-label">To</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group form-focus">
                                        <select class="form-control select2" name="interview_status" id="interviewStatus">
                                            <option value="">--Select--</option>
                                            @foreach($LeadStatuss as $LeadStatus)
                                                <option value="{{ $LeadStatus->leadstatusid }}" {{ old('interview_status', isset($selectedValues['interview_status']) ? $selectedValues['interview_status'] : '') == $LeadStatus->leadstatusid ? 'selected' : '' }}>{{ $LeadStatus->leadstatusname }}</option>
                                            @endforeach
                                        </select>
                                        <label class="focus-label">Interview Status</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-search rounded btn-block mb-3 mr-2">Search</button>
                                    <button type="button" id="resetButton" class="btn btn-secondary rounded btn-block mb-3">Reset</button>
                                </div>
                            </div>
                        </form> -->

                        <script>
                            document.getElementById('resetButton').addEventListener('click', function() {
                                // Reset the form
                                document.getElementById('filterForm').reset();
                                
                                // Reset Select2
                                $('#interviewStatus').val('').trigger('change');

                                // Reset datetimepicker
                                $('.datetimepicker-input').each(function() {
                                    $(this).datetimepicker('clear');
                                });
                            });
                        </script>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th> </th>
                                        <th style="display: none;">Id</th>
                                        <th>Company</th>
                                        <th>Vendor</th>
                                        <th>Interviewee</th>
                                        <th>Lead Comment</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                        <th>Created BY</th>
                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leads as $lead)
                                        <tr id="row-{{ $lead->id }}" style="{{ $lead->is_read ? 'color: red;' : '' }}">
                                            <td>
                                                <input type="checkbox" class="read-checkbox" data-leadid="{{ $lead->id }}" {{ $lead->is_read ? 'checked' : '' }}>
                                            </td>

                                            <td style="display: none;">{{ $lead->id }}</td>
                                            
                                            <td title="{{ $lead->company->company_name }}">
                                                <i class="fa fa-building"></i> 
                                                <span style="color: blue; font-weight: bold;">
                                                    {{ mb_strimwidth($lead->company->company_name, 0, 10, '...') }}
                                                </span><br>
                                              
                                            </td>
                                            <td>
                                                <i class="fa fa-user"></i> {{ $lead->vendor->name }}  <h2><a href="{{ route('leads.show', $lead->id) }}">{{ $lead->vendor->technology->technology_name }}</a></h2>
                                            </td>
                                            <td>
                                                <i class="fa fa-id-card"></i> {{ $lead->interviewer->firstname }}  <h2><a href="{{ route('leads.show', $lead->id) }}" title="Interview Date"> <i class="fas fa-calendar-alt"></i>{{ \Carbon\Carbon::parse($lead->interview_date)->format('d-m-Y') }}</a></h2>
                                            </td>

                                            <td title="{{ $lead->lead_comment }}">
                                             <textarea name="lead_comment" class="form-control" rows="2" readonly data-toggle="popover"  data-content="{{ $lead->lead_comment }}">{{ $lead->lead_comment }}</textarea>
                                            </td>
                                            
                                            <td>
                                                @php
                                                $statusId = $lead->leadStatus->leadstatusid;
                                                $badgeClass = '';
                                                $iconClass = '';

                                                switch ($statusId) {
                                                    case 1: // Screening
                                                        $badgeClass = 'badge-primary';
                                                        $iconClass = 'fas fa-search';
                                                        break;
                                                    case 2: // Introduction Call
                                                        $badgeClass = 'badge-secondary';
                                                        $iconClass = 'fas fa-phone';
                                                        break;
                                                    case 3: // Technical Round
                                                        $badgeClass = 'badge-info';
                                                        $iconClass = 'fas fa-cog';
                                                        break;
                                                    case 4: // Assessment Round (Machine Test)
                                                        $badgeClass = 'badge-success';
                                                        $iconClass = 'fas fa-file-alt';
                                                        break;
                                                    case 5: // Offer Letter/Onboarding
                                                        $badgeClass = 'badge-success';
                                                        $iconClass = 'fas fa-file-signature';
                                                        break;
                                                    case 6: // Offer letter received but not joined
                                                        $badgeClass = 'badge-danger';
                                                        $iconClass = 'fas fa-file-invoice';
                                                        break;
                                                    case 13: // Rejected
                                                        $badgeClass = 'badge-danger';
                                                        $iconClass = 'fas fa-times-circle';
                                                        break;
                                                    case 14: // Client Round
                                                        $badgeClass = 'badge-dark';
                                                        $iconClass = 'fas fa-users';
                                                        break;
                                                    default:
                                                        $badgeClass = 'badge-danger';
                                                        $iconClass = 'fas fa-question-circle';
                                                }
                                                @endphp

                                                <a href="{{ route('leads.show', $lead->id) }}" class="badge-link">
                                                    <span class="badge badge-custom {{ $badgeClass }}">
                                                        <i class="{{ $iconClass }}"></i> {{ $lead->leadStatus->leadstatusname }}
                                                    </span>
                                                </a>
                                            </td>

                                            <td class="text-right">
                                                <a href="{{ route('leads.edit', $lead->id) }}" class="btn btn-primary btn-sm mb-1">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <a href="{{ route('leads.show', $lead->id) }}" class="btn btn-warning btn-sm mb-1">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                            </td>
                                            <td>
                                                {{ $lead->createdUser->firstname }}
                                                <h2><a href="{{ route('leads.show', $lead->id) }}">{{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}</a></h2>
                                            </td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($lead->updated_at)->format('d M Y') }}
                                            </td>
                                          
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "bStateSave":true,
            "buttons": ["pdf", "print", "excel"],
            "order": [[1, "desc"]] // Order by the first column (ID) in descending order
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

    $(document).ready(function() {
        $('.select2').select2();

    });
</script>
<script>
    $(document).ready(function() {
        $(document).on('change', '.read-checkbox', function() {
            var checkbox = $(this);
            var leadId = checkbox.data('leadid');
            var isChecked = checkbox.prop('checked') ? 1 : 0;

            // Send AJAX request to update the 'is_read' column
            $.ajax({
                url: '/leads/' + leadId + '/update-read-status',
                type: 'POST',
                data: {
                    is_read: isChecked,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Handle success response
                    var row = $('#row-' + leadId); // Select the table row with dynamic ID
                    if (isChecked) {
                        row.css('color', 'red'); // Change the color of the corresponding table row to red
                    } else {
                        row.css('color', ''); // Remove the color property
                    }
                },
                error: function(xhr) {
                    // Handle error response if needed
                }
            });
        });
    });
</script>
<script src="assets/js/moment.min.js"></script>
    <div id="loader" class="loader-wrapper">
        <div class="loader"></div>
    </div>
    <!-- Include JavaScript libraries -->
<!-- <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script> -->
<!-- <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script> -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<!-- <script src="{{ asset('assets/js/moment.min.js') }}"></script> -->
<!-- <script src="{{ asset('assets/js/fullcalendar.min.js') }}"></script> -->
<!-- <script src="{{ asset('assets/js/jquery.fullcalendar.js') }}"></script> -->
<!-- <script src="{{ asset('assets/plugins/morris/morris.min.js') }}"></script> -->
<!-- <script src="{{ asset('assets/plugins/raphael/raphael-min.js') }}"></script> -->
<!-- <script src="{{ asset('assets/js/apexcharts.js') }}"></script> -->
<!-- <script src="{{ asset('assets/js/chart-data.js') }}"></script> -->
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- Include DataTables JavaScript -->
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datetimepicker/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- --------------------------------------------------------------- -->
<!-- Include DataTables button pdf and print excel -->
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<!-- <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script> -->
<!-- <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script> -->
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<!-- <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script> -->
<!-- --------------------------------------------------------------------- -->
<!-- Include custom scripts -->
<script src="{{ asset('js/loader.js') }}"></script>
<script src="{{ asset('js/live-search.js') }}"></script>
<script src="{{ asset('js/LeadByCompanyController.js') }}"></script>

<script>
$(function () {
    $('.datetimepicker').datetimepicker({
      format: 'DD-MM-YYYY'
    });
  });   
</script>

</body>
</html>  

