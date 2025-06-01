@extends('layouts.app')

@section('title', 'Создать поставку')

@section('content')
    <h1>Создать поставку</h1>
    <form method="POST" action="/admin/supplies">
        @csrf
        <div>
            <label for="store_id">Пункт выдачи:</label>
            <select name="store_id" id="store_id" required>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                @endforeach
            </select>
            @error('store_id')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label>Товары:</label>
            @foreach($products as $product)
                <div>
                    <label>
                        <input type="checkbox" class="product-checkbox" data-product-id="{{ $product->id }}"> {{ $product->name }}
                    </label>
                    <div class="product-variants" style="display: none;" data-product-id="{{ $product->id }}">
                        @foreach($product->colors as $color)
                            @foreach($product->sizes as $size)
                                <label>
                                    {{ $color->name }} / {{ $size->name }}:
                                    <input type="number" name="products[{{ $product->id }}][items][{{ $loop->parent->index }}_{{ $loop->index }}][quantity]" min="0" class="variant-quantity" disabled>
                                    <input type="hidden" name="products[{{ $product->id }}][items][{{ $loop->parent->index }}_{{ $loop->index }}][color_id]" value="{{ $color->id }}">
                                    <input type="hidden" name="products[{{ $product->id }}][items][{{ $loop->parent->index }}_{{ $loop->index }}][size_id]" value="{{ $size->id }}">
                                    <input type="hidden" name="products[{{ $product->id }}][id]" value="{{ $product->id }}">
                                </label>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @endforeach
            @error('products')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit">Создать поставку</button>
    </form>

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