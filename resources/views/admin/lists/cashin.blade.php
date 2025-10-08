@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All CashIn Lists</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">All Cashout Lists</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All CashIn Lists</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <a href="{{route('cash-in-create')}}" class="btn btn-primary add-new mb-2">Add Cash In</a>
                <div class="fetch-data table-responsive">
                    <table id="co-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                            	<th>UUID</th>
                                <th>UserName</th>
                                <th>Amount</th>
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
  		let ci_id;
  		var coTable = $('#co-table').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{url('/cashin-lists')}}",
		        },

		        columns: [
		            {data: 'uuid', name: 'uuid'},
                    {data: 'user', name: 'user'},
                    {data: 'amount', name: 'amount'},
		            {data: 'status', name: 'status'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });



       $(document).on('click', '#status-cashin-update', function(){

	         ci_id = $(this).data('id');
	         //alert()
	         var isCiChecked = $(this).prop('checked');
	         var status_val = isCiChecked ? 'Approved' : 'Pending';
              var user_id = "{{user()->id}}";
	         $.ajax({

                url: "{{url('/ci-status-update')}}",

                     type:"POST",
                     data:{'ci_id':ci_id, 'user_id':user_id, 'status':status_val},
                     dataType:"json",
                     success:function(data) {
                     	console.log(data);
                        toastr.success(data.message);

                        $('.data-table').DataTable().ajax.reload(null, false);

                },

	        });
       });


       $(document).on('click', '.delete-cashin', function(e){

           e.preventDefault();

           ci_id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({

                    url: "{{url('/delete-cashin')}}/"+ci_id,

                         type:"GET",
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
