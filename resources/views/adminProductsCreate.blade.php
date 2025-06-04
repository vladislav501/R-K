@extends('layouts.app')

@section('title', 'Создать товар')

@section('content')
<div class="products-manage-wrapper">
    <div class="products-nav-panel">
        <h2>Админ Панель</h2>
        <ul>
            <li><a href="{{ route('admin.products.index') }}">Управление товарами</a></li>
            <li><a href="{{ route('admin.brands.index') }}">Управление брендами</a></li>
            <li><a href="{{ route('admin.categories.index') }}">Управление категориями</a></li>
            <li><a href="{{ route('admin.clothing_types.index') }}">Управление типами одежды</a></li>
            <li><a href="{{ route('admin.collections.index') }}">Управление коллекциями</a></li>
            <li><a href="{{ route('admin.colors.index') }}">Управление цветами</a></li>
            <li><a href="{{ route('admin.sizes.index') }}">Управление размерами</a></li>
            <li><a href="{{ route('admin.pickup_points.index') }}">Управление пунктами выдачи</a></li>
            <li><a href="{{ route('admin.supplies.index') }}">Управление поставками</a></li>
            <li><a href="{{ route('admin.supplies.archive') }}">Архив поставок</a></li>
            <li><a href="{{ route('admin.orders.index') }}">Управление заказами</a></li>
        </ul>
    </div>
    <div class="products-main-content">
        <h1>Создать товар</h1>
        @if(session('error'))
            <div class="products-error-notice">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('admin.products.store') }}" class="products-create-form" enctype="multipart/form-data">
            @csrf
            <div class="products-form-group">
                <label for="name">Название:</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error('name') <span class="products-error-text">{{ $message }}</span> @enderror
            </div>
            <div class="products-form-group">
                <label for="price">Цена:</label>
                <input type="number" name="price" id="price" step="0.01" value="{{ old('price') }}" required>
                @error('price') <span class="products-error-text">{{ $message }}</span> @enderror
            </div>
            <div class="products-form-group">
                <label for="brand_id">Бренд:</label>
                <select name="brand_id" id="brand_id" required>
                    <option value="">Выберите бренд</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand_id') <span class="products-error-text">{{ $message }}</span> @enderror
            </div>
            <div class="products-form-group">
                <label for="category_id">Категория:</label>
                <select name="category_id" id="category_id" required>
                    <option value="">Выберите категорию</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="products-error-text">{{ $message }}</span> @enderror
            </div>
            <div class="products-form-group">
                <label for="collection_id">Коллекция:</label>
                <select name="collection_id" id="collection_id">
                    <option value="">Без коллекции</option>
                    @foreach($collections as $collection)
                        <option value="{{ $collection->id }}" {{ old('collection_id') == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                    @endforeach
                </select>
                @error('collection_id') <span class="products-error-text">{{ $message }}</span> @enderror
            </div>
            <div class="products-form-group">
                <label for="clothing_type_id">Тип одежды:</label>
                <select name="clothing_type_id" id="clothing_type_id" required>
                    <option value="">Выберите тип</option>
                    @foreach($clothingTypes as $clothingType)
                        <option value="{{ $clothingType->id }}" {{ old('clothing_type_id') == $clothingType->id ? 'selected' : '' }}>{{ $clothingType->name }}</option>
                    @endforeach
                </select>
                @error('clothing_type_id') <span class="products-error-text">{{ $message }}</span> @enderror
            </div>
            <div class="products-form-group">
                <label>Изображения (до 3):</label>
                <div class="products-image-upload">
                    <div>
                        <label for="image_1">Изображение 1:</label>
                        <input type="file" name="image_1" id="image_1" accept="image/jpeg,image/png,image/jpg">
                        @error('image_1') <span class="products-error-text">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="image_2">Изображение 2:</label>
                        <input type="file" name="image_2" id="image_2" accept="image/jpeg,image/png,image/jpg">
                        @error('image_2') <span class="products-error-text">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="image_3">Изображение 3:</label>
                        <input type="file" name="image_3" id="image_3" accept="image/jpeg,image/png,image/jpg">
                        @error('image_3') <span class="products-error-text">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="products-form-group">
                <label>Цвета:</label>
                <div class="products-checkbox-group">
                    @foreach($colors as $color)
                        <label>
                            <input type="checkbox" name="colors[]" value="{{ $color->id }}" {{ in_array($color->id, old('colors', [])) ? 'checked' : '' }}> {{ $color->name }}
                        </label>
                    @endforeach
                </div>
                @error('colors') <span class="products-error-text">{{ $message }}</span> @enderror
            </div>
            <div class="products-form-group">
                <label>Размеры:</label>
                <div class="products-checkbox-group">
                    @foreach($sizes as $size)
                        <label>
                            <input type="checkbox" name="sizes[]" value="{{ $size->id }}" {{ in_array($size->id, old('sizes', [])) ? 'checked' : '' }}> {{ $size->name }}
                        </label>
                    @endforeach
                </div>
                @error('sizes') <span class="products-error-text">{{ $message }}</span> @enderror
            </div>
            <div class="products-form-group">
                <label>Количество по цветам и размерам:</label>
                @foreach($colors as $color)
                    <h3>{{ $color->name }}</h3>
                    <div class="products-quantity-grid">
                        @foreach($sizes as $size)
                            <div class="products-quantity-item">
                                <label>{{ $size->name }}:</label>
                                <input type="number" name="color_size_quantities[{{ $color->id }}][{{ $size->id }}]" min="0" value="{{ old("color_size_quantities.{$color->id}.{$size->id}", 0) }}">
                            </div>
                        @endforeach
                    </div>
                @endforeach
                @error('color_size_quantities') <span class="products-error-text">{{ $message }}</span> @enderror
            </div>
            <div class="products-form-group">
                <label>Пункты выдачи:</label>
                <div class="products-checkbox-group">
                    @foreach($stores as $store)
                        <label>
                            <input type="checkbox" name="stores[]" value="{{ $store->id }}" class="store-checkbox" {{ in_array($store->id, old('stores', [])) ? 'checked' : '' }}> {{ $store->name }}
                            <input type="number" name="store_quantities[{{ $store->id }}]" class="store-quantity" min="0" value="{{ old("store_quantities.{$store->id}") }}">
                        </label>
                    @endforeach
                </div>
                @error('stores') <span class="products-error-text">{{ $message }}</span> @enderror
                @error('store_quantities') <span class="products-error-text">{{ $message }}</span> @enderror
            </div>
            <div class="products-form-group">
                <label>
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', 1) ? 'checked' : '' }}> В наличии
                    <input type="hidden" name="is_available" value="0">
                </label>
                @error('is_available') <span class="products-error-text">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="products-submit-button">Создать</button>
        </form>
        <a href="{{ route('admin.products.index') }}" class="products-back-link">Назад</a>
    </div>
</div>

<script>
    document.querySelectorAll('.store-checkbox').forEach(checkbox => {
        const quantityInput = checkbox.nextElementSibling;
        quantityInput.style.display = checkbox.checked ? 'inline' : 'none';
        quantityInput.required = checkbox.checked;

        checkbox.addEventListener('change', function() {
            quantityInput.style.display = this.checked ? 'inline' : 'none';
            quantityInput.required = this.checked;
            if (!this.checked) {
                quantityInput.value = '';
            }
        });
    });
</script>
@endsection