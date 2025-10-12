@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Gift Box</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/gifts')}}">All Gift Box</a></li>
                        <li class="breadcrumb-item active">Add Gift Box</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add Gift Box</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{route('gifts.store')}}" method="POST" enctype="multipart/form-data">
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
                            <div class="form-group">
                                <label for="task_will_block">No. of task will be blocked for gift <span class="required">*</span></label>
                                <input type="number" name="task_will_block" class="form-control numericInput" id="task_will_block"
                                       placeholder="No. of task will be blocked for gift" required="" value="{{ old('task_will_block') }}">
                                @error('task_will_block')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="frozen_amounts">Reserved Amount (BDT) </label>
                                <input type="text" name="frozen_amounts" class="form-control numericInput" id="frozen_amounts"
                                       placeholder="Reserved Amount (BDT)" value="{{ old('frozen_amounts') }}">
                                @error('frozen_amounts')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="frozen_amount_task_will_block">No. of task will be blocked for frozen amount</label>
                                <input type="number" name="frozen_amount_task_will_block" class="form-control numericInput" id="frozen_amount_task_will_block"
                                       placeholder="No. of task will be blocked for frozen amount" value="{{ old('frozen_amount_task_will_block') }}">
                                @error('frozen_amount_task_will_block')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Value </label>
                                <input
                                    type="text"
                                    name="frozen_value"
                                    class="form-control numericInput"
                                    placeholder="Enter value"
                                    value="{{ old('frozen_value') }}"
                                >
                                @error('frozen_value')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Unit </label>
                                <select name="frozen_unit" class="form-control" required>
                                    <option value="X">X</option>
                                    {{--                                    <option value="Taka">Taka</option>--}}
                                </select>
                                @error('frozen_unit')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <h5 class="mt-4">Gift Box 1: </h5>
                    <div class="row">
                        {{-- Gift Box 1 --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Amount <span class="required">*</span></label>
                                <input type="text" name="gift_boxes[0][value]" class="form-control" placeholder="Enter amount" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Unit <span class="required">*</span></label>
                                <select name="gift_boxes[0][unit]" class="form-control" required>
                                    <option value="Taka">Taka</option>
                                    <option value="X">X</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 pl-5 mt-4">
                            <div class="form-check">
                                <input type="radio" name="active_gift" value="0" class="form-check-input" checked>
                                <label class="ml-2">Active?</label>
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4">Gift Box 2: </h5>
                    <div class="row">
                        {{-- Gift Box 2 --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Amount <span class="required">*</span></label>
                                <input type="text" name="gift_boxes[1][value]" class="form-control" placeholder="Enter amount" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Unit <span class="required">*</span></label>
                                <select name="gift_boxes[1][unit]" class="form-control" required>
                                    <option value="Taka">Taka</option>
                                    <option value="X">X</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 pl-5 mt-4">
                            <div class="form-check">
                                <input type="radio" name="active_gift" value="1" class="form-check-input">
                                <label class="ml-2">Active?</label>
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4">Gift Box 3: </h5>
                    <div class="row">
                        {{-- Gift Box 3 --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Amount <span class="required">*</span></label>
                                <input type="text" name="gift_boxes[2][value]" class="form-control" placeholder="Enter amount" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Unit <span class="required">*</span></label>
                                <select name="gift_boxes[2][unit]" class="form-control" required>
                                    <option value="Taka">Taka</option>
                                    <option value="X">X</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 pl-5 mt-4">
                            <div class="form-check">
                                <input type="radio" name="active_gift" value="2" class="form-check-input">
                                <label class="ml-2">Active?</label>
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

  </script>

@endpush
