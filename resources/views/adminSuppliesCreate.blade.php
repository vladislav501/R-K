@extends('layouts.app')

@section('title', 'Создать поставку')

@section('content')
<div class="supply-page-container">
    <h1 class="supply-title">Создать поставку</h1>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('admin.supplies.store') }}" method="POST" class="supply-form">
        @csrf

        <div class="supply-form-group">
            <label for="pickup_point_id" class="supply-label">Пункт выдачи:</label>
            <select name="pickup_point_id" id="pickup_point_id" class="header-store-select" required>
                <option value="">Выберите пункт выдачи</option>
                @foreach($pickupPoints as $pp)
                    <option value="{{ $pp->id }}" {{ old('pickup_point_id') == $pp->id ? 'selected' : '' }}>
                        {{ $pp->name }} ({{ $pp->address }})
                    </option>
                @endforeach
            </select>
            @error('pickup_point_id')<span class="error">{{ $message }}</span>@enderror
        </div>

        <label class="supply-label">Товары:</label>
        @foreach($products as $index => $product)
            @php
                $colorSizes = $product->colorSizes()->with(['color', 'size'])->get();
            @endphp

            <div class="supply-product">
                <div class="supply-product-header">
                    @if($product->image_1)
                        <img src="{{ Storage::url($product->image_1) }}" alt="{{ $product->name }}" class="supply-product-image">
                    @else
                        <span>Нет изображения</span>
                    @endif

                    <label class="supply-product-label">
                        <input type="checkbox" class="product-checkbox" data-index="{{ $index }}">
                        {{ $product->name }}
                    </label>
                </div>

                <div class="product-variants" data-index="{{ $index }}">
                    <input type="hidden" name="products[{{ $index }}][id]" value="{{ $product->id }}">

                    @forelse($colorSizes as $cs)
                        <div class="supply-variant">
                            <label>
                                <span>{{ $cs->color->name }} / {{ $cs->size->name }}</span>
                                <input type="hidden" name="products[{{ $index }}][items][{{ $cs->color_id }}_{{ $cs->size_id }}][color_id]" value="{{ $cs->color_id }}">
                                <input type="hidden" name="products[{{ $index }}][items][{{ $cs->color_id }}_{{ $cs->size_id }}][size_id]" value="{{ $cs->size_id }}">
                                <input type="number" name="products[{{ $index }}][items][{{ $cs->color_id }}_{{ $cs->size_id }}][quantity]" class="variant-quantity" min="0" value="0" disabled>
                            </label>
                        </div>
                    @empty
                        <div class="text-muted" style="margin-left:1rem;">
                            ⚠ Нет комбинаций цвет/размер
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach

        <button type="submit" class="supply-submit">Создать поставку</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.variant-quantity').forEach(i => i.value = 0);

document.querySelectorAll('.product-checkbox').forEach(cb => {
    cb.addEventListener('change', function () {
        const idx = cb.dataset.index;
        document.querySelectorAll(`.product-variants[data-index="${idx}"] .variant-quantity`)
            .forEach(input => {
                input.disabled = !cb.checked;
                if (!cb.checked) input.value = 0;
            });
    });
});

document.querySelector('.supply-form').addEventListener('submit', function (e) {
    let formValid = false;

    document.querySelectorAll('.product-checkbox').forEach(cb => {
        const idx = cb.dataset.index;
        const productBlock = document.querySelector(`.product-variants[data-index="${idx}"]`);
        const inputs = productBlock.querySelectorAll('.variant-quantity');

        let hasQuantity = false;
        inputs.forEach(input => {
            if (parseInt(input.value) > 0) {
                hasQuantity = true;
                formValid = true;
            }
        });

        if (!hasQuantity) {
            cb.closest('.supply-product').remove();
        }
    });

    if (!formValid) {
        e.preventDefault();
        alert('Выберите хотя бы один товар и укажите кол-во > 0');
    }
});
</script>
@endsection

