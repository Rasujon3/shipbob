@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Task</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/tasks')}}">All Task
                                </a></li>
                        <li class="breadcrumb-item active">Edit Task</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Edit Task</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{route('tasks.update',$task->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Task Title <span class="required">*</span></label>
                                <input type="text" name="title" class="form-control" id="title"
                                       placeholder="Task Title" required="" value="{{old('title',$task->title)}}">
                                @error('title')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="num_of_task">Number of Task <span class="required">*</span></label>
                                <input type="text" name="num_of_task" class="form-control numericInput" id="num_of_task"
                                       placeholder="Number of Task" required="" value="{{old('num_of_task',$task->num_of_task)}}">
                                @error('num_of_task')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{--<div class="col-md-12">
                            <div class="form-group">
                                <label for="commission">Commission BDT <span class="required">*</span></label>
                                <input type="text" name="commission" class="form-control numericInput" id="commission"
                                       placeholder="Commission" required="" value="{{old('commission',$task->commission)}}">
                                @error('commission')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>--}}

{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="time_duration">Time Duration <span class="required">*</span></label>--}}
{{--                                <input type="text" name="time_duration" class="form-control numericInput" id="time_duration"--}}
{{--                                       placeholder="Time Duration" required="" value="{{ old('time_duration',$task->time_duration) }}">--}}
{{--                                @error('time_duration')--}}
{{--                                <span class="alert alert-danger">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="time_unit">Time Unit <span class="required">*</span></label>--}}
{{--                                <select class="form-control select2bs4" name="time_unit" id="time_unit" required="">--}}
{{--                                    <option value="" selected="" disabled="">Select Unit</option>--}}
{{--                                    <option value="Day" @if($task->time_unit === "Day") selected @endif>Day</option>--}}
{{--                                    <option value="Month" @if($task->time_unit === "Month") selected @endif>Month</option>--}}
{{--                                    <option value="Year" @if($task->time_unit === "Year") selected @endif>Year</option>--}}
{{--                                    <option value="Hour" @if($task->time_unit === "Hour") selected @endif>Hour</option>--}}
{{--                                    <option value="Minute" @if($task->time_unit === "Minute") selected @endif>Minute</option>--}}
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


  <script>

  </script>

@endpush
