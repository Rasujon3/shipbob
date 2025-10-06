@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All Assign Trial Task</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="{{URL::to('/trial-task-assign')}}">All Assign Trial Task</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Assign Trial Task</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <a href="{{ route('trial-task-assign.create') }}" class="btn btn-primary add-new mb-2">Add New Assign Trial Task</a>
                <div class="fetch-data table-responsive">
                    <table id="product-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Trial Task</th>
                                <th>Num of Tasks</th>
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
  		var productTable = $('#product-table').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{ url('/trial-task-assign') }}",
		        },

		        columns: [
		            {data: 'name', name: 'name'},
                    {data: 'task', name: 'task'},
                    {data: 'num_of_tasks', name: 'num_of_tasks'},
                    {data: 'status', name: 'status'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });

       $(document).on('click', '.delete-AssignedTrialTask', function(e){

           e.preventDefault();

           product_id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({

                    url: "{{url('/trial-task-assign')}}/"+product_id,

                         type:"DELETE",
                         dataType:"json",
                         success:function(data) {

                        if (!data.status) {
                            toastr.error(data.message);
                        } else {
                            toastr.success(data.message);
                        }

                        $('.data-table').DataTable().ajax.reload(null, false);

                    },

              });
           }

       });

  	});
  </script>

@endpush
