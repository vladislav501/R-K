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
                        <div class="product-variants" data-product-id="{{ $product->id }}">
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
        // Set all quantity inputs to 0 on page load
        document.querySelectorAll('.variant-quantity').forEach(input => {
            input.value = '0';
        });

        document.querySelectorAll('.product-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const productId = this.dataset.productId;
                const variantsDiv = document.querySelector(`.product-variants[data-product-id="${productId}"]`);
                const inputs = variantsDiv.querySelectorAll('.variant-quantity');
                inputs.forEach(input => {
                    input.disabled = !this.checked;
                    if (!this.checked) {
                        input.value = '0';
                    }
                });
            });
        });

        document.querySelector('.supply-form').addEventListener('submit', function(event) {
            let hasValidProduct = false;
            const productsData = [];

            document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                const productId = checkbox.dataset.productId;
                const variantsDiv = document.querySelector(`.product-variants[data-product-id="${productId}"]`);
                const quantityInputs = variantsDiv.querySelectorAll('.variant-quantity');
                const allInputs = variantsDiv.querySelectorAll('input');

                if (!checkbox.checked) {
                    // Disable all inputs for unchecked products
                    allInputs.forEach(input => {
                        input.disabled = true;
                        if (input.classList.contains('variant-quantity')) {
                            input.value = '0';
                        }
                    });
                } else {
                    let hasValidQuantity = false;
                    quantityInputs.forEach(input => {
                        const quantity = parseInt(input.value);
                        if (isNaN(quantity) || quantity <= 0) {
                            input.disabled = true;
                            input.value = '0';
                            input.closest('.supply-variant').querySelectorAll('input').forEach(i => i.disabled = true);
                        } else {
                            hasValidQuantity = true;
                        }
                    });
                    if (hasValidQuantity) {
                        hasValidProduct = true;
                        productsData.push(productId);
                    } else {
                        // Disable all inputs if no valid quantities
                        allInputs.forEach(input => {
                            input.disabled = true;
                            if (input.classList.contains('variant-quantity')) {
                                input.value = '0';
                            }
                        });
                    }
                }
            });

            // Prevent submission if no valid products
            if (!hasValidProduct) {
                event.preventDefault();
                alert('Выберите хотя бы один товар с количеством больше 0.');
            } else {
                console.log('Submitting products:', productsData);
            }
        });
    </script>
@endsection