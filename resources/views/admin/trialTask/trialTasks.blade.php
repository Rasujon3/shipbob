@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Trial Task</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/trial-task')}}">Trial Task
                                </a></li>
                        <li class="breadcrumb-item active">Trial Task</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Trial Task</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('update-trial-task') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="num_of_task">Number of Task <span class="required">*</span></label>
                                <input type="text" name="num_of_task" class="form-control numericInput" id="num_of_task"
                                       placeholder="Number of Task" required="" value="{{old('num_of_task', ($trialTask && $trialTask->num_of_task) ? $trialTask->num_of_task : "")}}">
                                @error('num_of_task')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{--<div class="col-md-6">
                            <div class="form-group">
                                <label for="commission">Commission BDT <span class="required">*</span></label>
                                <input type="text" name="commission" class="form-control numericInput" id="commission"
                                       placeholder="Commission" required=""
                                       value="{{old('commission', ($trialTask && $trialTask->commission) ? $trialTask->commission : "")}}">
                                @error('commission')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>--}}

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trial_balance">Trial Balance (BDT) <span class="required">*</span></label>
                                <input type="text" name="trial_balance" class="form-control numericInput" id="trial_balance"
                                       placeholder="Trial Balance" required=""
                                       value="{{old('trial_balance', ($trialTask && $trialTask->trial_balance) ? $trialTask->trial_balance : "")}}">
                                @error('trial_balance')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

{{--                    <div class="row">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="time_duration">Time Duration <span class="required">*</span></label>--}}
{{--                                <input type="text" name="time_duration" class="form-control numericInput" id="time_duration"--}}
{{--                                       placeholder="Time Duration" required=""--}}
{{--                                       value="{{old('time_duration', ($trialTask && $trialTask->time_duration) ? $trialTask->time_duration : "")}}">--}}
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
{{--                                    <option value="Day" @if($trialTask && $trialTask->time_unit === "Day") selected @endif>Day</option>--}}
{{--                                    <option value="Month" @if($trialTask && $trialTask->time_unit === "Month") selected @endif>Month</option>--}}
{{--                                    <option value="Year" @if($trialTask && $trialTask->time_unit === "Year") selected @endif>Year</option>--}}
{{--                                    <option value="Hour" @if($trialTask && $trialTask->time_unit === "Hour") selected @endif>Hour</option>--}}
{{--                                    <option value="Minute" @if($trialTask && $trialTask->time_unit === "Minute") selected @endif>Minute</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="form-group w-100">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </div>
                    <!-- /.card-body -->
            </form>
        </div>
    </section>
</div>
@endsection
