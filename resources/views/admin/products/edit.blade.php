@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2>Edit Product</h2>

    <form method="POST" action="{{ route('products.update',$product->id) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        <div class="mb-3">
            <label>Add More Images</label>
            <input type="file" name="images[]" class="form-control" multiple>
        </div>

        <div class="mb-3">
            @foreach($product->images as $img)
            <img src="{{ asset('storage/'.$img->image_path) }}" width="80" class="mr-2 mb-2">
            @endforeach
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection