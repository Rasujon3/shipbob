@extends('admin_master')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Gift Box</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{URL::to('/gifts')}}">All Gift Box</a></li>
                            <li class="breadcrumb-item active">Edit Gift Box</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Gift Box - {{ $gift->user->username ?? 'N/A' }} ({{ $gift->user->uid ?? 'N/A' }})</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{route('gifts.update', $gift->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">Select User <span class="required">*</span></label>
                                    <select class="form-control select2bs4" name="user_id" id="user_id" required="">
                                        <option value="" selected="" disabled="">Select User</option>
                                        @if(count($users) > 0)
                                            @foreach ($users as $user)
                                                <option value="{{ $user['id'] }}" @if($user['id'] === $gift->user_id) selected @endif>{{ $user['username'] }} ({{ $user['uid'] }})</option>
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
                                           placeholder="No. of task will be blocked for gift" required=""
                                           value="{{ old('task_will_block', $gift->task_will_block) }}">
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
                                           placeholder="Reserved Amount (BDT)"
                                           value="{{ old('frozen_amounts', $gift->frozen_amounts) }}">
                                    @error('frozen_amounts')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="frozen_amount_task_will_block">No. of task will be blocked for frozen amount</label>
                                    <input type="number" name="frozen_amount_task_will_block" class="form-control numericInput" id="frozen_amount_task_will_block"
                                           placeholder="No. of task will be blocked for frozen amount"
                                           value="{{ old('frozen_amount_task_will_block', $gift->frozen_amount_task_will_block) }}">
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
                                        value="{{ old('frozen_value', $gift->frozen_value) }}"
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

                        @php
                            // Get existing gift boxes or create default array
                            $giftBoxes = $gift->giftBox->toArray();

                            // Ensure we have exactly 3 boxes
                            while(count($giftBoxes) < 3) {
                                $giftBoxes[] = ['value' => '', 'unit' => 'Taka', 'is_active' => 0];
                            }

                            // Find which box is active
                            $activeGiftIndex = 0;
                            foreach($giftBoxes as $index => $box) {
                                if(isset($box['is_active']) && $box['is_active']) {
                                    $activeGiftIndex = $index;
                                    break;
                                }
                            }

                            // Use old input if validation failed
                            if(old('active_gift') !== null) {
                                $activeGiftIndex = old('active_gift');
                            }
                        @endphp

                        @for($i = 0; $i < 3; $i++)
                            <h5 class="mt-4">Gift Box {{ $i + 1 }}: </h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Amount <span class="required">*</span></label>
                                        <input type="text" name="gift_boxes[{{ $i }}][value]" class="form-control"
                                               placeholder="Enter amount" required
                                               value="{{ old('gift_boxes.'.$i.'.value', $giftBoxes[$i]['value'] ?? '') }}">
                                        @error('gift_boxes.'.$i.'.value')
                                        <span class="alert alert-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Unit <span class="required">*</span></label>
                                        <select name="gift_boxes[{{ $i }}][unit]" class="form-control" required>
                                            <option value="Taka"
                                                {{ old('gift_boxes.'.$i.'.unit', $giftBoxes[$i]['unit'] ?? 'Taka') == 'Taka' ? 'selected' : '' }}>
                                                Taka
                                            </option>
                                            <option value="X"
                                                {{ old('gift_boxes.'.$i.'.unit', $giftBoxes[$i]['unit'] ?? 'Taka') == 'X' ? 'selected' : '' }}>
                                                X
                                            </option>
                                        </select>
                                        @error('gift_boxes.'.$i.'.unit')
                                        <span class="alert alert-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 pl-5 mt-4">
                                    <div class="form-check">
                                        <input type="radio" name="active_gift" value="{{ $i }}" class="form-check-input"
                                            {{ $activeGiftIndex == $i ? 'checked' : '' }}>
                                        <label class="ml-2">Active?</label>
                                    </div>
                                </div>
                            </div>
                        @endfor

                        @error('active_gift')
                        <div class="row">
                            <div class="col-12">
                                <span class="alert alert-danger">{{ $message }}</span>
                            </div>
                        </div>
                        @enderror

                        <div class="form-group w-100 px-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Gift Box
                            </button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </form>
            </div>
        </section>
    </div>
@endsection

    @push('scripts')

    @endpush
