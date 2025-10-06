@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Withdraw Password</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/updateUser')}}">All User</a></li>
                        <li class="breadcrumb-item active">Edit Withdraw Password</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Edit Withdraw Password ({{ $updateUser->username ?? '' }})</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{route('updateUser.withdraw-password-update',$updateUser->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="new_withdraw_password">Withdraw Password <span class="required">*</span></label>
                                <input type="password" name="new_withdraw_password" class="form-control" id="new_withdraw_password"
                                    placeholder="Withdraw Password" required="" value="{{old('new_withdraw_password')}}">
                                @error('new_withdraw_password')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="new_withdraw_password_confirmation">Confirm Withdraw Password <span class="required">*</span></label>
                                <input type="password" name="new_withdraw_password_confirmation" class="form-control" id="new_withdraw_password_confirmation"
                                    placeholder="Withdraw Password" required="" value="{{old('new_withdraw_password_confirmation')}}">
                                @error('new_withdraw_password_confirmation')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group w-100 px-2">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>
        </div>
    </section>
</div>

@endsection

@push('scripts')


  <script>

  </script>

@endpush
