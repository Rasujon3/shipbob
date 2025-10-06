@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/settings')}}">Settings
                                </a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Settings</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{url('settings-app')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_name">Company name <span class="required">*</span></label>
                                <input type="text" name="company_name" class="form-control" id="company_name"
                                    placeholder="Company name"  value="{{old('company_name', ($setting && $setting->company_name) ? $setting->company_name : "")}}">
                                @error('company_name')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telegram_group_link">Telegram group Link <span class="required">*</span></label>
                                <input type="text" name="telegram_group_link" class="form-control numericInput" id="telegram_group_link"
                                       placeholder="Telegram group Link"  value="{{old('telegram_group_link', $setting ? $setting->telegram_group_link : "")}}">
                                @error('telegram_group_link')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="company_logo">Company Logo <span class="required">*</span></label>
                                <input
                                    name="company_logo"
                                    type="file"
                                    id="company_logo"
                                    accept="image/*"
                                    class="dropify"
                                    data-height="150"
                                    data-default-file="{{ URL::to($setting ? $setting->company_logo : '') }}"
                                />
                                @error('company_logo')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

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
