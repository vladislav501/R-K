@extends('layouts.app')

@section('title', 'Создать поставку')

@section('content')
    <div class="supply-page-container">
        <h1 class="supply-title">Создать поставку</h1>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.supplies.store') }}" method="POST" class="supply-form">
            @csrf
            <div class="supply-form-group">
                <label for="pickup_point_id" class="supply-label">Пункт выдачи:</label>
                <select name="pickup_point_id" id="pickup_point_id" class="header-store-select" required>
                    <option value="">Выберите пункт выдачи</option>
                    @foreach($pickupPoints as $pickupPoint)
                        <option value="{{ $pickupPoint->id }}" {{ old('pickup_point_id') == $pickupPoint->id ? 'selected' : '' }}>{{ $pickupPoint->name }} ({{ $pickupPoint->address }})</option>
                    @endforeach
                </select>
                @error('pickup_point_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="supply-label">Товары:</label>
                @foreach($products as $index => $product)
                    @php
                        $colorSizes = \App\Models\ProductColorSize::where('product_id', $product->id)
                            ->with(['color', 'size'])
                            ->get();
                    @endphp
                    @if($colorSizes->isNotEmpty())
                        <div class="supply-product">
                            <div class="supply-product-header">
                                @if($product->image_1)
                                    <img src="{{ Storage::url($product->image_1) }}" alt="{{ $product->name }}" class="supply-product-image">
                                @else
                                    Нет изображения
                                @endif
                                <label class="supply-product-label">
                                    <input type="checkbox" class="product-checkbox" data-product-index="{{ $index }}">
                                    <span class="supply-product-name">{{ $product->name }}</span>
                                </label>
                            </div>
                            <div class="product-variants" data-product-index="{{ $index }}">
                                <input type="hidden" name="products[{{ $index }}][id]" value="{{ $product->id }}">
                                @foreach($colorSizes as $colorSize)
                                    <div class="supply-variant">
                                        <label class="supply-variant-label">
                                            <span class="supply-variant-info">{{ $colorSize->color->name }} / {{ $colorSize->size->name }}</span>
                                            <input type="hidden" name="products[{{ $index }}][items][{{ $colorSize->color_id }}_{{ $colorSize->size_id }}][color_id]" value="{{ $colorSize->color_id }}">
                                            <input type="hidden" name="products[{{ $index }}][items][{{ $colorSize->color_id }}_{{ $colorSize->size_id }}][size_id]" value="{{ $colorSize->size_id }}">
                                            <input type="number" name="products[{{ $index }}][items][{{ $colorSize->color_id }}_{{ $colorSize->size_id }}][quantity]" class="variant-quantity supply-input" min="0" value="0" disabled>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
                @error('products')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="supply-submit">Создать поставку</button>
        </form>
    </div>
@endsection

{{-- @section('styles')
<style>
    .supply-page-container { max-width: 900px; margin: 0 auto; }
    .supply-title { font-size: 28px; margin-bottom: 20px; }
    .supply-label { font-weight: bold; display: block; margin-bottom: 8px; }
    .supply-form-group { margin-bottom: 20px; }
    .supply-product { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; }
    .supply-product-header { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
    .supply-product-image { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; }
    .supply-product-name { font-size: 16px; margin-left: 8px; }
    .supply-variant { margin-bottom: 10px; }
    .supply-variant-info { display: inline-block; width: 150px; }
    .supply-input { width: 100px; padding: 6px; }
    .supply-submit { margin-top: 20px; padding: 10px 20px; font-size: 16px; background-color: #007bff; color: #fff; border: none; border-radius: 6px; cursor: pointer; }
    .supply-submit:hover { background-color: #0056b3; }
    .error { color: red; font-size: 13px; }
</style>
@endsection --}}

@section('scripts')
<script>
    document.querySelectorAll('.variant-quantity').forEach(input => {
        input.value = '0';
    });

    document.querySelectorAll('.product-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const index = this.dataset.productIndex;
            const variantsDiv = document.querySelector(`.product-variants[data-product-index="${index}"]`);
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

        document.querySelectorAll('.product-checkbox').forEach(checkbox => {
            const index = checkbox.dataset.productIndex;
            const variantsDiv = document.querySelector(`.product-variants[data-product-index="${index}"]`);
            const quantityInputs = variantsDiv.querySelectorAll('.variant-quantity');
            let hasValidQuantity = false;

            quantityInputs.forEach(input => {
                const quantity = parseInt(input.value);
                if (!isNaN(quantity) && quantity > 0) {
                    hasValidQuantity = true;
                }
            });

            if (!hasValidQuantity) {
                quantityInputs.forEach(input => {
                    input.disabled = true;
                    input.value = '0';
                });
            } else {
                hasValidProduct = true;
            }
        });

        if (!hasValidProduct) {
            event.preventDefault();
            alert('Выберите хотя бы один товар с количеством больше 0.');
        }
    });
</script>
@endsection