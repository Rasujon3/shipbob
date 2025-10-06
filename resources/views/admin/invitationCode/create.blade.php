@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Invitation Codes</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/invitation-codes')}}">All Invitation Codes
                                </a></li>
                        <li class="breadcrumb-item active">Add Invitation Codes</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add Invitation Codes</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('invitation-codes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_id">Select User <span class="required">*</span></label>
                                <select class="form-control select2bs4" name="user_id" id="user_id" required="">
                                    <option value="" selected="" disabled="">Select User</option>
                                    @if(count($users) > 0)
                                        @foreach ($users as $user)
                                            <option value="{{ $user['id'] }}">{{ $user['username'] }} ({{ $user['uid'] }})</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('user_id')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="code">Invitation Code <span class="required">*</span></label>
                                        <input type="text" name="code" class="form-control" id="code"
                                               placeholder="Invitation Code" required="" value="{{old('code')}}">
                                        @error('code')
                                            <span class="alert alert-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-center mt-2 mt-md-3">
                                    <button type="button" class="btn btn-primary w-100" id="generateCode">Generate Code</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="note">Note </label>
                                <textarea class="form-control" name="note">{!!old('note')!!}</textarea>
                                @error('note')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group w-100 px-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                <!-- /.card-body -->
            </form>
        </div>
    </section>
</div>

@endsection

@push('scripts')

    <script>
        $('#generateCode').on('click', function() {
            $.ajax({
              url: '/generate-code', // your Laravel route
              type: 'GET',
              success: function(response) {
                  if (response.status && response.code !== '') {
                      $('#code').val(response.code);
                  } else {
                      $('#code').val('');
                  }
              },
              error: function() {
                  alert("Something went wrong!");
              }
            });
        });
    </script>

@endpush
