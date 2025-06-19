@extends('layouts.app')

@section('title', 'Создать поставку')

@section('content')
    <div class="supply-page-container">
        <h1 class="supply-title">Создать поставку</h1>
        <form method="POST" action="/admin/supplies" class="supply-form">
            @csrf
            <div>
                <label for="pickup_point_id" class="supply-label">Пункт выдачи:</label>
                <select name="pickup_point_id" id="pickup_point_id" class="header-store-select" required>
                    <option value="">Выберите пункт выдачи</option>
                    @foreach(\App\Models\PickupPoint::all() as $pickupPoint)
                        <option value="{{ $pickupPoint->id }}" {{ old('pickup_point_id', request('pickup_point_id')) == $pickupPoint->id ? 'selected' : '' }}>{{ $pickupPoint->name }}</option>
                    @endforeach
                </select>
                @error('pickup_point_id')
                    <span class="error">{{ $message }}</span>
                @enderror
                <input type="hidden" name="store_id" value="{{ old('pickup_point_id', request('pickup_point_id')) }}">
            </div>
            <div>
                <label class="supply-label">Товары:</label>
                @foreach($products as $product)
                    <div class="supply-product">
                        <div class="supply-product-header">
                            @if($product->image_1)
                                    <img src="{{ Storage::url($product->image_1) }}" alt="{{ $product->name }}" class="supply-product-image">
                            @else
                                Нет изображения
                            @endif
                            <label class="supply-product-label">
                                <input type="checkbox" class="product-checkbox" data-product-id="{{ $product->id }}"> 
                                <span class="supply-product-name">{{ $product->name }}</span>
                            </label>
                        </div>
                        <div class="product-variants" style="display: none;" data-product-id="{{ $product->id }}">
                            @foreach($product->colors as $color)
                                @foreach($product->sizes as $size)
                                    <div class="supply-variant">
                                        <label class="supply-variant-label">
                                            <span class="supply-variant-info">{{ $color->name }} / {{ $size->name }}</span>
                                            <input type="number" name="products[{{ $product->id }}][items][{{ $loop->parent->index }}_{{ $loop->index }}][quantity]" min="0" class="variant-quantity supply-input" disabled>
                                            <input type="hidden" name="products[{{ $product->id }}][items][{{ $loop->parent->index }}_{{ $loop->index }}][color_id]" value="{{ $color->id }}">
                                            <input type="hidden" name="products[{{ $product->id }}][items][{{ $loop->parent->index }}_{{ $loop->index }}][size_id]" value="{{ $size->id }}">
                                            <input type="hidden" name="products[{{ $product->id }}][id]" value="{{ $product->id }}">
                                        </label>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endforeach
                @error('products')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="supply-submit">Создать поставку</button>
        </form>
    </div>

    <script>
        document.querySelectorAll('.product-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const productId = this.dataset.productId;
                const variantsDiv = document.querySelector(`.product-variants[data-product-id="${productId}"]`);
                const inputs = variantsDiv.querySelectorAll('.variant-quantity');
                variantsDiv.style.display = this.checked ? 'block' : 'none';
                inputs.forEach(input => {
                    input.disabled = !this.checked;
                    input.required = this.checked;
                    if (!this.checked) input.value = '';
                });
            });
        });
    </script>
@endsection