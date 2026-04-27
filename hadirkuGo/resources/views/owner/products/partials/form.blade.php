@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-3">
    <label for="name" class="form-label">Product Name</label>
    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
</div>
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" class="form-control" id="description">{{ old('description') }}</textarea>
</div>
<div class="mb-3">
    <label for="image" class="form-label">Product Image</label>
    <input type="file" name="image" class="form-control" id="image">
    @if(isset($product) && $product->image)
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="100" class="mt-2" id="product-image-preview">
    @endif
</div>
<div class="mb-3">
    <label for="stock_quantity" class="form-label">Stock Quantity</label>
    <input type="number" name="stock_quantity" class="form-control" id="stock_quantity" value="{{ old('stock_quantity') ?? 0 }}" required>
</div>
<div class="mb-3">
    <label for="points_required" class="form-label">Points Required</label>
    <input type="number" name="points_required" class="form-control" id="points_required" value="{{ old('points_required') ?? 0 }}" required>
</div>
