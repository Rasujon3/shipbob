@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All Welcome Bonus</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">
                            <a href="{{ URL::to('/welcome-bonuses') }}">All Welcome Bonus</a>
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Welcome Bonus</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <a href="{{ route('welcome-bonuses.create') }}" class="btn btn-primary add-new mb-2">Add New Welcome Bonus</a>
                <div class="fetch-data table-responsive">
                    <table id="product-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Number of Tasks</th>
                                <th>Completed Tasks</th>
                                <th>Remaining Tasks</th>
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
  		let data_id;
  		var productTable = $('#product-table').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{ url('/welcome-bonuses') }}",
		        },

		        columns: [
		            {data: 'name', name: 'name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'num_of_tasks', name: 'num_of_tasks'},
                    {data: 'completed_tasks', name: 'completed_tasks'},
                    {data: 'remaining_tasks', name: 'remaining_tasks'},
                    {data: 'status', name: 'status'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });

       $(document).on('click', '.delete-data', function(e){

           e.preventDefault();

           data_id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({
                    url: "{{url('/welcome-bonuses')}}/"+data_id,
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
