@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Login Page Content</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/settings')}}">Login Page Content
                                </a></li>
                        <li class="breadcrumb-item active">Login Page Content</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Login Page Content</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ url('update-login-page-content') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="img">Background Image <span class="required">*</span></label>
                                <input
                                    name="img"
                                    type="file"
                                    id="img"
                                    accept="image/*"
                                    class="dropify"
                                    data-height="150"
                                    data-default-file="{{ URL::to($loginPageContent ? $loginPageContent->img : '') }}"
                                />
                                @error('img')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Name <span class="required">*</span></label>
                                <textarea
                                    name="name"
                                    class="form-control description"
                                    id="name"
                                    placeholder="Name"
                                >
                                    {!! old('name', $loginPageContent ? $loginPageContent->name : "") !!}
                                </textarea>
                                @error('name')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Title <span class="required">*</span></label>
                                <textarea
                                    name="title"
                                    class="form-control description"
                                    id="title"
                                    placeholder="Title"
                                >
                                    {!! old('title', $loginPageContent ? $loginPageContent->title : "") !!}
                                </textarea>
                                @error('title')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="description">Description <span class="required">*</span></label>
                                <textarea
                                    name="description"
                                    class="form-control description"
                                    id="description"
                                    placeholder="Description"
                                >
                                    {!! old('description', $loginPageContent ? $loginPageContent->description : "") !!}
                                </textarea>
                                @error('description')
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
