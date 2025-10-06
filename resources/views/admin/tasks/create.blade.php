@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Task</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/products')}}">All Task
                                </a></li>
                        <li class="breadcrumb-item active">Add Task</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add Task</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{route('tasks.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Task Title <span class="required">*</span></label>
                                <input type="text" name="title" class="form-control" id="title"
                                    placeholder="Task Title" required="" value="{{old('title')}}">
                                @error('title')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="num_of_task">Number of Task <span class="required">*</span></label>
                                <input type="text" name="num_of_task" class="form-control numericInput" id="num_of_task"
                                    placeholder="Number of Task" required="" value="{{old('num_of_task')}}">
                                @error('num_of_task')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{--<div class="col-md-12">
                            <div class="form-group">
                                <label for="commission">Commission BDT <span class="required">*</span></label>
                                <input type="text" name="commission" class="form-control numericInput" id="commission"
                                       placeholder="Commission" required="" value="{{old('commission')}}">
                                @error('commission')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>--}}

{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="time_duration">Time Duration <span class="required">*</span></label>--}}
{{--                                <input type="text" name="time_duration" class="form-control numericInput" id="time_duration"--}}
{{--                                       placeholder="Time Duration" required="" value="{{ old('time_duration') }}">--}}
{{--                                @error('time_duration')--}}
{{--                                    <span class="alert alert-danger">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="time_unit">Time Unit <span class="required">*</span></label>--}}
{{--                                <select class="form-control select2bs4" name="time_unit" id="time_unit" required="">--}}
{{--                                    <option value="" selected="" disabled="">Select Unit</option>--}}
{{--                                    <option value="Day">Day</option>--}}
{{--                                    <option value="Month">Month</option>--}}
{{--                                    <option value="Year">Year</option>--}}
{{--                                    <option value="Hour">Hour</option>--}}
{{--                                    <option value="Minute">Minute</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}

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
