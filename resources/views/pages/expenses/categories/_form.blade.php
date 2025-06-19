{{-- resources/views/pages/expenses/categories/_form.blade.php --}}

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Please correct the errors below:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="form-group mb-3">
    <label for="name">Category Name <span class="text-danger">*</span></label>
    <input type="text" name="name" id="name" class="form-control"
           value="{{ old('name', $category->name ?? '') }}" required autofocus>
    @error('name')
        <div class="text-danger-small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="form-group mb-3">
    <label for="description">Description (Optional)</label>
    <textarea name="description" id="description" class="form-control"
              rows="3">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description')
        <div class="text-danger-small mt-1">{{ $message }}</div>
    @enderror
</div>