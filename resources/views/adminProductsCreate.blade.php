@extends('layouts.app')

@section('title', 'Создать товар')

@section('content')
    <h1>Создать товар</h1>
    <form method="POST" action="{{ route('admin.products.store') }}">
        @csrf
        <div>
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="price">Цена:</label>
            <input type="number" name="price" id="price" step="0.01" value="{{ old('price') }}" required>
            @error('price') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="brand_id">Бренд:</label>
            <select name="brand_id" id="brand_id" required>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                @endforeach
            </select>
            @error('brand_id') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="category_id">Категория:</label>
            <select name="category_id" id="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="collection_id">Коллекция:</label>
            <select name="collection_id" id="collection_id">
                <option value="">Без коллекции</option>
                @foreach($collections as $collection)
                    <option value="{{ $collection->id }}" {{ old('collection_id') == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                @endforeach
            </select>
            @error('collection_id') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="clothing_type_id">Тип одежды:</label>
            <select name="clothing_type_id" id="clothing_type_id" required>
                @foreach($clothingTypes as $clothingType)
                    <option value="{{ $clothingType->id }}" {{ old('clothing_type_id') == $clothingType->id ? 'selected' : '' }}>{{ $clothingType->name }}</option>
                @endforeach
            </select>
            @error('clothing_type_id') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div>
            <label>Цвета:</label>
            @foreach($colors as $color)
                <label>
                    <input type="checkbox" name="colors[]" value="{{ $color->id }}" {{ in_array($color->id, old('colors', [])) ? 'checked' : '' }}> {{ $color->name }}
                </label>
            @endforeach
            @error('colors') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div>
            <label>Размеры:</label>
            @foreach($sizes as $size)
                <label>
                    <input type="checkbox" name="sizes[]" value="{{ $size->id }}" {{ in_array($size->id, old('sizes', [])) ? 'checked' : '' }}> {{ $size->name }}
                </label>
            @endforeach
            @error('sizes') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div>
        <label>Количество по цветам и размерам:</label>
            @foreach($colors as $color)
                <h3>{{ $color->name }}</h3>
                @foreach($sizes as $size)
                    <label>
                        {{ $size->name }}:
                        <input type="number" name="color_size_quantities[{{ $color->id }}][{{ $size->id }}]" min="0" placeholder="Количество" value="{{ old("color_size_quantities.{$color->id}.{$size->id}", 0) }}">
                    </label>
                @endforeach
            @endforeach
            @error('color_size_quantities') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div>
            <label>Пункты выдачи:</label>
            @foreach($stores as $store)
                <label>
                    <input type="checkbox" name="stores[]" value="{{ $store->id }}" class="store-checkbox" {{ in_array($store->id, old('stores', [])) ? 'checked' : '' }}> {{ $store->name }}
                    <input type="number" name="store_quantities[]" class="store-quantity" style="display: none;" min="0" placeholder="Количество" value="{{ old('store_quantities.' . $loop->index) }}">
                </label>
            @endforeach
            @error('stores') <span class="error">{{ $message }}</span> @enderror
            @error('store_quantities') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div>
            <label>
                <input type="checkbox" name="is_available" value="1" {{ old('is_available', 1) ? 'checked' : '' }}> В наличии
                <input type="hidden" name="is_available" value="0">
            </label>
            @error('is_available') <span class="error">{{ $message }}</span> @enderror
        </div>
        <button type="submit">Создать</button>
    </form>

    <script>
        document.querySelectorAll('.store-checkbox').forEach(checkbox => {
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