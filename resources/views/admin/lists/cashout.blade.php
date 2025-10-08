@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All Cashout Lists</h1>
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
                <h3 class="card-title">All Cashout Lists</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="fetch-data table-responsive">
                    <table id="co-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>UserName</th>
                                <th>Phone</th>
                                <th>Acc No</th>
                                <th>Method</th>
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
  		let co_id;
  		var coTable = $('#co-table').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{url('/cashout-lists')}}",
		        },

		        columns: [
		            {data: 'uuid', name: 'uuid'},
                    {data: 'user', name: 'user'},
                    {data: 'phone', name: 'phone'},
                    {data: 'acc_no', name: 'acc_no'},
                    {data: 'method', name: 'method'},
                    {data: 'amount', name: 'amount'},
		            {data: 'status', name: 'status'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });



       $(document).on('click', '#status-cashout-update', function(){

	         co_id = $(this).data('id');
	         var isCoChecked = $(this).prop('checked');
	         var status_val = isCoChecked ? 'Approved' : 'Pending';
           var user_id = "{{user()->id}}";
	         $.ajax({

                url: "{{url('/co-status-update')}}",

                     type:"POST",
                     data:{'co_id':co_id, 'user_id':user_id, 'status':status_val},
                     dataType:"json",
                     success:function(data) {

                        toastr.success(data.message);

                        $('.data-table').DataTable().ajax.reload(null, false);

                },

	        });
       });


       $(document).on('click', '.delete-cashout', function(e){

           e.preventDefault();

           co_id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({

                    url: "{{url('/delete-cashout')}}/"+co_id,

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
