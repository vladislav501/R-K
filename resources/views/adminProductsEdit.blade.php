@extends('layouts.app')

@section('title', 'Редактировать товар')

@section('content')
    <h1>Редактировать товар</h1>
    <form method="POST" action="{{ route('admin.products.update', $product) }}">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" value="{{ $product->name }}" required>
            @error('name')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="price">Цена:</label>
            <input type="number" name="price" id="price" step="0.01" value="{{ $product->price }}" required>
            @error('price')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="brand_id">Бренд:</label>
            <select name="brand_id" id="brand_id" required>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                @endforeach
            </select>
            @error('brand_id')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="category_id">Категория:</label>
            <select name="category_id" id="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="collection_id">Коллекция:</label>
            <select name="collection_id" id="collection_id">
                <option value="">Без коллекции</option>
                @foreach($collections as $collection)
                    <option value="{{ $collection->id }}" {{ $product->collection_id == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                @endforeach
            </select>
            @error('collection_id')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="clothing_type_id">Тип одежды:</label>
            <select name="clothing_type_id" id="clothing_type_id" required>
                @foreach($clothingTypes as $clothingType)
                    <option value="{{ $clothingType->id }}" {{ $product->clothing_type_id == $clothingType->id ? 'selected' : '' }}>{{ $clothingType->name }}</option>
                @endforeach
            </select>
            @error('clothing_type_id')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label>Цвета:</label>
            @foreach($colors as $color)
                <label>
                    <input type="checkbox" name="colors[]" value="{{ $color->id }}" {{ $product->colors->contains($color->id) ? 'checked' : '' }}> {{ $color->name }}
                </label>
            @endforeach
            @error('colors')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label>Размеры:</label>
            @foreach($sizes as $size)
                <label>
                    <input type="checkbox" name="sizes[]" value="{{ $size->id }}"> {{ $size->name }}
                    <input type="number" name="size_quantities[{{ $size->id }}]" value="{{ $product->sizes->contains($size->id) ? $product->pivot->quantity ?? 0 : 0 }}" style="display: none;" min="0" placeholder="Количество">
                </label>
            @endforeach
            @error('sizes')
                <span>{{ $message }}</span>
            @enderror
            @error('size_quantities')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label>Пункты выдачи:</label>
            @foreach($stores as $store)
                <label>
                    <input type="checkbox" name="stores[]" value="{{ $store->id }}"> {{ $store->name }}
                    <input type="number" name="store_quantities[{{ $store->id }}]" value="{{ $product->stores->contains($store->id) ? $product->pivot->quantity ?? 0 : 0 }}" style="display: none;" min="0" placeholder="Количество">
                </label>
            @endforeach
            @error('stores')
                <span>{{ $message }}</span>
            @enderror
            @error('store_quantities')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label>Количество по цветам и размерам:</label>
            @foreach($colors as $color)
                <h3>{{ $color->name }}</h3>
                @foreach($sizes as $size)
                    <label>
                        {{ $size->name }}:
                        <input type="number" name="color_size_quantities[{{ $color->id }}][{{ $size->id }}]" value="{{ isset($colorSizes[$color->id][$size->id]) ? $colorSizes[$color->id][$size->id]->first()->quantity : 0 }}" min="0" placeholder="Количество">
                    </label>
                @endforeach
            @endforeach
            @error('color_size_quantities')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label>
                <input type="checkbox" name="is_available" value="1" {{ $product->is_available ? 'checked' : '' }}> В наличии
                <input type="hidden" name="is_available" value="0">
            </label>
            @error('is_available')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <button type="submit">Обновить</button>
    </form>

    <script>
        document.querySelectorAll('input[name="stores[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const quantityInput = this.nextElementSibling;
                quantityInput.style.display = this.checked ? 'inline' : 'none';
                quantityInput.required = this.checked;
                if (!this.checked) {
                    quantityInput.value = '';
                }
            });
            const quantityInput = checkbox.nextElementSibling;
            quantityInput.style.display = checkbox.checked ? 'inline' : 'none';
            quantityInput.required = checkbox.checked;
        });

        document.querySelectorAll('input[name="sizes[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const quantityInput = this.nextElementSibling;
                quantityInput.style.display = this.checked ? 'inline' : 'none';
                quantityInput.required = this.checked;
                if (!this.checked) {
                    quantityInput.value = '';
                }
            });
            const quantityInput = checkbox.nextElementSibling;
            quantityInput.style.display = checkbox.checked ? 'inline' : 'none';
            quantityInput.required = checkbox.checked;
        });
    </script>
@endsection