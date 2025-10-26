@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Welcome Bonus</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/welcome-bonuses')}}">All Welcome Bonus</a></li>
                        <li class="breadcrumb-item active">Add Welcome Bonus</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add Welcome Bonus</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('welcome-bonuses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
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

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="amount">Amount <span class="required">*</span></label>
                                <input
                                    type="text"
                                    name="amount"
                                    class="form-control numericInput"
                                    id="amount"
                                    placeholder="Amount"
                                    required=""
                                    value="{{ old('amount') }}"
                                >
                                @error('amount')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="num_of_tasks">Number of Tasks <span class="required">*</span></label>
                                <input
                                    type="text"
                                    name="num_of_tasks"
                                    class="form-control numericInput"
                                    id="num_of_tasks"
                                    placeholder="Number of Tasks"
                                    required=""
                                    value="{{ old('num_of_tasks',80) }}"
                                >
                                @error('num_of_tasks')
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
