@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All Assign Round Trial Task</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="{{URL::to('/rtt/rtt-assign-tasks')}}">All Assign Round Trial Task</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Assign Round Trial Task</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <a href="{{ route('rtt-assign-tasks.create') }}" class="btn btn-primary add-new mb-2">Add New Assign Round Trial Task</a>
                <div class="fetch-data table-responsive">
                    <table id="product-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Round Trial Task</th>
                                <th>Num of Tasks</th>
                                <th>Status</th>
{{--                                <th>Action</th>--}}
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
		          url: "{{ url('/rtt/rtt-assign-tasks') }}",
		        },

		        columns: [
		            {data: 'name', name: 'name'},
                    {data: 'task', name: 'task'},
                    {data: 'num_of_tasks', name: 'num_of_tasks'},
                    {data: 'status', name: 'status'},
		            // {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });

       $(document).on('click', '.delete-data', function(e){

           e.preventDefault();

           product_id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({

                    url: "{{url('/rtt/rtt-assign-tasks')}}/"+product_id,

                         type:"DELETE",
                         dataType:"json",
                         success:function(data) {

                            toastr.success(data.message);

                            $('.data-table').DataTable().ajax.reload(null, false);

                    },

              });
           }

       });

  	});
  </script>

@endpush
