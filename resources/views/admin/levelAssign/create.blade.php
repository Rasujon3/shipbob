@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Assign Level</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/level-assign')}}">All Assign Level</a></li>
                        <li class="breadcrumb-item active">Add Assign Level</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add Assign Level</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('level-assign.store') }}" method="POST" enctype="multipart/form-data">
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
                                            <option value="{{ $user['id'] }}">{{ $user['name'] }} ({{ $user['uid'] }})</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('user_id')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="level_id">Select Level <span class="required">*</span></label>
                                <select class="form-control select2bs4" name="level_id" id="level_id" required="">
                                    <option value="" selected="" disabled="">Select Level</option>
                                    @if(count($levels) > 0)
                                        @foreach ($levels as $level)
                                            <option value="{{ $level['id'] }}">{{ $level['title'] }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('level_id')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group w-100 px-2">
                            <button type="submit" class="btn btn-primary">Submit</button>
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

  <script src="{{asset('custom/multiple_files.js')}}"></script>

  <script>

  </script>

@endpush
