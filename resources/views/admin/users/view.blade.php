@extends('admin_master')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">View User Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">All User</a></li>
                            <li class="breadcrumb-item active">View User</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">User Information</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Title -->
                        <div class="col-md-6 mb-3">
                            <label for="title"><strong>UID:</strong></label>
                            <p class="form-control-plaintext border p-2 bg-light">{{ $updateUser->uid ?? 'N/A' }}</p>
                        </div>
                        <!-- Title -->
                        <div class="col-md-6 mb-3">
                            <label for="title"><strong>Status:</strong></label>
                            <p class="form-control-plaintext border p-2 bg-light">{{ $updateUser->status ?? 'N/A' }}</p>
                        </div>

                        <!-- Title -->
                        <div class="col-md-6 mb-3">
                            <label for="title"><strong>Username:</strong></label>
                            <p class="form-control-plaintext border p-2 bg-light">{{ $updateUser->username ?? 'N/A' }}</p>
                        </div>
                        <!-- Title -->
                        <div class="col-md-6 mb-3">
                            <label for="title"><strong>Phone:</strong></label>
                            <p class="form-control-plaintext border p-2 bg-light">{{ $updateUser->phone ?? 'N/A' }}</p>
                        </div>

                        <!-- Title -->
                        <div class="col-md-6 mb-3">
                            <label for="title"><strong>Withdraw Acc. No:</strong></label>
                            <p class="form-control-plaintext border p-2 bg-light">{{ $updateUser->withdraw_acc_number ?? 'N/A' }}</p>
                        </div>
                        <!-- Title -->
                        <div class="col-md-6 mb-3">
                            <label for="title"><strong>Balance:</strong></label>
                            <p class="form-control-plaintext border p-2 bg-light">{{ $updateUser->main_balance ?? 'N/A' }}</p>
                        </div>
                        <!-- Title -->
                        <div class="col-md-6 mb-3">
                            <label for="title"><strong>Address:</strong></label>
                            <p class="form-control-plaintext border p-2 bg-light">{{ $updateUser->address ?? 'N/A' }}</p>
                        </div>

                        <!-- Updated At -->
                        <div class="col-md-6 mb-3">
                            <label><strong>Last Login Time:</strong></label>
                            <p class="form-control-plaintext border p-2 bg-light">
                                {{ $updateUser->updated_at ? $updateUser->updated_at->format('d M Y, h:i A') : 'N/A' }}
                            </p>
                        </div>

                        <!-- Back Button -->
                        <div class="form-group w-100 px-2 mt-3">
                            <a href="{{ route('updateUser.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            <a href="{{ route('updateUser.edit', $updateUser->id) }}" class="btn btn-success">
                                <i class="fas fa-edit"></i> Change Login Password
                            </a>
                            <a href="{{ route('updateUser.withdraw-password-edit', $updateUser->id) }}" class="btn btn-success">
                                <i class="fas fa-edit"></i> Change Withdraw Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
