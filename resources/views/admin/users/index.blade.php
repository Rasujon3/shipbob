@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All User</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">All User</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All User</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="fetch-data table-responsive">
                    <table id="product-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Phone</th>
                                <th>Total Trial Task</th>
                                <th>Completed Trial Task</th>
                                <th>Total Assigned Task</th>
                                <th>Completed Task</th>
                                <th>Remaining Task</th>

{{--                                <th>Total Assigned RTT Task</th>--}}
{{--                                <th>Completed RTT Task</th>--}}
{{--                                <th>Remaining RTT Task</th>--}}

                                <th>View RTT</th>

                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="conts">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- User View Modal -->
    <div class="modal fade" id="userViewModal" tabindex="-1" role="dialog" aria-labelledby="userViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userViewModalLabel">User Details</h5>
                    <!-- Bootstrap 4 Close Button -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="userViewModalContent">
                    <!-- AJAX দিয়ে কনটেন্ট এখানে আসবে -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->

    <!-- RTT Modal -->
    <div class="modal fade" id="rttModal" tabindex="-1" role="dialog" aria-labelledby="rttModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="rttModalLabel">RTT Task Summary</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered mb-0">
                        <tbody>
                        <tr>
                            <th>Total Assigned RTT</th>
                            <td id="rttTotalAssigned">—</td>
                        </tr>
                        <tr>
                            <th>Completed RTT Tasks</th>
                            <td id="rttCompleted">—</td>
                        </tr>
                        <tr>
                            <th>Remaining RTT Tasks</th>
                            <td id="rttRemaining">—</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->

</div>
@endsection

@push('scripts')

  <script>
  	$(document).ready(function(){
  		let product_id;
  		let user_id;

  		var productTable = $('#product-table').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{ url('/updateUser') }}",
		        },

		        columns: [
		            {data: 'name', name: 'name'},
		            {data: 'location', name: 'location'},
		            {data: 'phone', name: 'phone'},
		            {data: 'total_trial_task', name: 'total_trial_task'},
		            {data: 'completed_trial_task', name: 'completed_trial_task'},
		            {data: 'total_assigned_task', name: 'total_assigned_task'},
		            {data: 'completed_task', name: 'completed_task'},
		            {data: 'remaining_task', name: 'remaining_task'},

		            // {data: 'total_assigned_rtt', name: 'total_assigned_rtt'},
		            // {data: 'completed_rtt_task', name: 'completed_rtt_task'},
		            // {data: 'remaining_rtt_task', name: 'remaining_rtt_task'},

		            {data: 'view_rtt', name: 'view_rtt'},

                    {data: 'status', name: 'status'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });

       $(document).on('click', '.delete-product', function(e){

           e.preventDefault();

           product_id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({

                    url: "{{url('/updateUser')}}/"+product_id,

                         type:"DELETE",
                         dataType:"json",
                         success:function(data) {

                            toastr.success(data.message);

                            $('.data-table').DataTable().ajax.reload(null, false);

                    },

              });
           }

       });

        $(document).on('click', '#status-user-update', function(){

            user_id = $(this).data('id');
            var isUserChecked = $(this).prop('checked');
            var status_val = isUserChecked ? 'Active' : 'Inactive';
            $.ajax({

                url: "{{ url('/user-status-update') }}",

                type: "POST",
                data:{ 'user_id': user_id, 'status': status_val },
                dataType: "json",
                success: function(data) {
                    toastr.success(data.message);

                    $('.data-table').DataTable().ajax.reload(null, false);
                },
            });
        });

        $(document).on('click', '.view-data', function(e) {
            e.preventDefault();
            let userId = $(this).data('id');

            $.ajax({
                url: "{{ url('/updateUser') }}/" + userId,
                type: 'GET',
                success: function(response) {
                    $('#userViewModalContent').html(response);
                    $('#userViewModal').modal('show');
                },
                error: function() {
                    alert('Failed to load user details.');
                }
            });
        });

        $(document).on('click', '.view-rtt', function (e) {
            e.preventDefault();
            let userId = $(this).data('id');

            $.ajax({
                url: `/updateUser/${userId}/rtt-stats`,
                type: 'GET',
                beforeSend: function() {
                    $('#rttTotalAssigned').text('Loading...');
                    $('#rttCompleted').text('Loading...');
                    $('#rttRemaining').text('Loading...');
                },
                success: function(response) {
                    if (response.status) {
                        $('#rttTotalAssigned').text(response.data.total_assigned_rtt);
                        $('#rttCompleted').text(response.data.completed_rtt_task);
                        $('#rttRemaining').text(response.data.remaining_rtt_task);
                    } else {
                        $('#rttTotalAssigned').text('Error');
                        $('#rttCompleted').text('Error');
                        $('#rttRemaining').text('Error');
                    }
                    $('#rttModal').modal('show');
                },
                error: function(xhr) {
                    $('#rttTotalAssigned').text('Error');
                    $('#rttCompleted').text('Error');
                    $('#rttRemaining').text('Error');
                    $('#rttModal').modal('show');
                }
            });
        });

    });
  </script>

@endpush
