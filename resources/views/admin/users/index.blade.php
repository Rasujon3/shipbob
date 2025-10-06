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
                                <th>Total Assigned Task</th>
                                <th>Completed Task</th>
                                <th>Remaining Task</th>
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
		            {data: 'total_assigned_task', name: 'total_assigned_task'},
		            {data: 'completed_task', name: 'completed_task'},
		            {data: 'remaining_task', name: 'remaining_task'},
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

  	});
  </script>

@endpush
