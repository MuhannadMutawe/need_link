@extends('layout.adminDash')
@section('content')
<div class="container" style="padding: 20px;">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h3>Create Category</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Category Name:</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Icon (Optional HTML Class or Text):</label>
                    <input type="text" name="icon" class="form-control" placeholder="e.g. bi-star">
                </div>
                <button type="submit" class="btn btn-primary">Create Category</button>
            </form>
        </div>
    </div>

    <hr>

    <h3 class="mb-4">Manage Categories</h3>
    @if(isset($categories) && $categories->count() > 0)
        <div class="row">
            @foreach($categories as $category)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ $category->name }} (ID: {{ $category->id }})</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dashboard.categories.update', $category->id) }}" method="POST" class="mb-3">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">Category Name:</label>
                                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Icon:</label>
                                    <input type="text" name="icon" class="form-control" value="{{ $category->icon }}">
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-warning">Update</button>
                            </form>
                                    <form action="{{ route('dashboard.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">No categories found.</div>
    @endif
</div>
@endsection
