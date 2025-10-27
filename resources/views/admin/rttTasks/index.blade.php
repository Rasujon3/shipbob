@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All Round Trial Tasks</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">All Round Trial Tasks</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Round Trial Tasks</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <a href="{{route('rtt-tasks.create')}}" class="btn btn-primary add-new mb-2">Add New Round Trial Tasks</a>
                <div class="fetch-data table-responsive">
                    <table id="product-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Num. of Task</th>
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
  		let id;
  		var productTable = $('#product-table').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{ url('/rtt/rtt-tasks') }}",
		        },

		        columns: [
		            {data: 'title', name: 'title'},
                    {data: 'num_of_task', name: 'num_of_task'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });

       $(document).on('click', '.delete-data', function(e){

           e.preventDefault();

           id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({
                    url: "{{ url('/rtt/rtt-tasks') }}/"+id,
                         type:"DELETE",
                         dataType:"json",
                         success:function(data) {
                             if(data.status){
                                 toastr.success(data.message);
                             } else {
                                 toastr.error(data.message);
                             }

                            toastr.success(data.message);

                            $('.data-table').DataTable().ajax.reload(null, false);
                    },
              });
           }
       });

  	});
  </script>

@endpush
