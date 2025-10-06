@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/products')}}">All Product
                                </a></li>
                        <li class="breadcrumb-item active">Edit Product</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Edit Product</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{route('products.update',$product->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Product Name <span class="required">*</span></label>
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="Product Name" required="" value="{{old('name',$product->name)}}">
                                @error('name')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price">Product Price (BDT) <span class="required">*</span></label>
                                <input type="text" name="price" class="form-control numericInput" id="price"
                                       placeholder="Product Price" required="" value="{{old('price',$product->price)}}">
                                @error('price')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description <span class="required">*</span></label>
                                <textarea class="form-control description" required="" name="description">{!!old('description',$product->description)!!}</textarea>
                                @error('description')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="commission">Commission BDT <span class="required">*</span></label>
                                <input type="text" name="commission" class="form-control numericInput" id="commission"
                                       placeholder="Commission" required="" value="{{old('commission',$product->commission)}}">
                                @error('commission')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>File</label>
                                        <div class="drop-container">
                                            <label for="file-input" class="upload-button">Upload Files</label>
                                            <div class="preview-images" id="preview-container"></div>
                                            <input type="file" class="form-control" name="file" id="file-input">
                                        </div>
                                        @error('file')
                                            <span class="alert alert-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group pt-5">
                                        <label for="img" class="mr-4">Image Preview</label>
                                        <img src="{{ asset($product->file) }}" alt="Product file" style="height:60px;">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-group w-100 px-2">
                            <button type="submit" class="btn btn-success">Save Changes</button>
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
