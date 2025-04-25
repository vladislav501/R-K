@extends('layouts.asset')
@section('content')
    <div class="add-product-content bg-white rounded-xl shadow-lg p-6 md:p-8 max-w-6xl mx-auto my-10">
        <h1 class="add-product-title text-3xl md:text-4xl font-montserrat-bold text-gray-800 text-center mb-8">Редактировать товар</h1>
        <form action="{{ route('product.update', $product->id) }}" method="post" class="add-product-form">
            @csrf
            @method('patch')
            <div class="add-product-form-container grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="add-product-form-group">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Бренд</label>
                    <select name="brandId" class="add-product-form-select w-full p-3 border border-gray-300 rounded-md focus:border-red-600" required>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $product->brandId == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="add-product-form-group">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Пол</label>
                    <select name="sex" class="add-product-form-select w-full p-3 border border-gray-300 rounded-md focus:border-red-600" required>
                        <option value="Мужской" {{ $product->sex == 'Мужской' ? 'selected' : '' }}>Мужской</option>
                        <option value="Женский" {{ $product->sex == 'Женский' ? 'selected' : '' }}>Женский</option>
                        <option value="Унисекс" {{ $product->sex == 'Унисекс' ? 'selected' : '' }}>Унисекс</option>
                    </select>
                </div>
                <div class="add-product-form-group">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Тип одежды</label>
                    <select name="typeId" class="add-product-form-select w-full p-3 border border-gray-300 rounded-md focus:border-red-600" required>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ $product->typeId == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="add-product-form-group">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Категория</label>
                    <select name="categoryId" class="add-product-form-select w-full p-3 border border-gray-300 rounded-md focus:border-red-600" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->categoryId == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="add-product-form-group">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Коллекция</label>
                    <select name="collectionId" class="add-product-form-select w-full p-3 border border-gray-300 rounded-md focus:border-red-600" required>
                        @foreach($collections as $collection)
                            <option value="{{ $collection->id }}" {{ $product->collectionId == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="add-product-form-group">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Название товара</label>
                    <input name="title" value="{{ $product->title }}" class="add-product-form-input w-full p-3 border border-gray-300 rounded-md" placeholder="Название товара" required>
                </div>
                <div class="add-product-form-group">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Короткое название</label>
                    <input name="shortTitle" value="{{ $product->shortTitle }}" class="add-product-form-input w-full p-3 border border-gray-300 rounded-md" placeholder="Краткое название" required>
                </div>
                <div class="add-product-form-group md:col-span-2">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Описание товара</label>
                    <textarea name="description" class="add-product-form-input w-full p-3 border border-gray-300 rounded-md" placeholder="Описание" rows="4" required>{{ $product->description }}</textarea>
                </div>
                <div class="add-product-form-group">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Цвета</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['Red' => '#FF0000', 'Orange' => '#FFA500', 'Yellow' => '#FFFF00', 'Green' => '#008000', 'Blue' => '#0000FF', 'Purple' => '#800080', 'Brown' => '#A52A2A', 'Black' => '#000000'] as $colorName => $colorCode)
                            <label class="color-option cursor-pointer">
                                <input type="checkbox" name="color[]" value="{{ $colorName }}" class="hidden color-checkbox">
                                <span class="color-circle inline-block w-8 h-8 rounded-full border-2 border-gray-300" style="background-color: {{ $colorCode }};"></span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="add-product-form-group">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Размеры</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                            <label class="size-option cursor-pointer">
                                <input type="checkbox" name="size[]" value="{{ $size }}" class="hidden size-checkbox" {{ in_array($size, $product->size ?? []) ? 'checked' : '' }}>
                                <span class="size-button w-10 h-10 flex items-center justify-center rounded-md border-2 {{ in_array($size, $product->size ?? []) ? 'bg-red-600 text-white border-red-600' : 'bg-gray-200 text-gray-800 border-gray-300' }}">{{ $size }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="add-product-form-group">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Цена (BYN)</label>
                    <input name="price" type="number" step="0.01" value="{{ $product->price }}" class="add-product-form-input w-full p-3 border border-gray-300 rounded-md" placeholder="Цена" required>
                </div>
                <div class="add-product-form-group">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Состав</label>
                    <textarea name="composition" class="add-product-form-input w-full p-3 border border-gray-300 rounded-md" placeholder="Состав" rows="3" required>{{ $product->composition }}</textarea>
                </div>
                <div class="add-product-form-group">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Страна дизайна</label>
                    <input name="designCountry" value="{{ $product->designCountry }}" class="add-product-form-input w-full p-3 border border-gray-300 rounded-md" placeholder="Страна дизайна" required>
                </div>
                <div class="add-product-form-group">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Страна производства</label>
                    <input name="manufacturenCountry" value="{{ $product->manufacturenCountry }}" class="add-product-form-input w-full p-3 border border-gray-300 rounded-md" placeholder="Страна производства" required>
                </div>
                <div class="add-product-form-group">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Импортер</label>
                    <input name="importer" value="{{ $product->importer }}" class="add-product-form-input w-full p-3 border border-gray-300 rounded-md" placeholder="Импортер" required>
                </div>
                <div class="add-product-form-group">
                    <label class="add-product-form-label text-lg font-semibold text-gray-700">Наличие</label>
                    <input type="checkbox" name="availability" value="1" class="add-product-checkbox h-5 w-5" {{ $product->availability ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-600">В наличии</span>
                </div>
            </div>
            <div class="flex gap-4 mt-8">
                <button type="submit" class="add-product-form-button w-full py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all">Обновить товар</button>
                <a href="{{ route('admin.index') }}" class="add-product-form-button w-full py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all text-center">Назад</a>
            </div>
        </form>
    </div>

    <script>
        document.querySelectorAll('.color-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const circle = this.nextElementSibling;
                if (this.checked) {
                    circle.classList.add('border-red-600', 'scale-110');
                } else {
                    circle.classList.remove('border-red-600', 'scale-110');
                }
            });
        });

        document.querySelectorAll('.size-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const button = this.nextElementSibling;
                if (this.checked) {
                    button.classList.add('bg-red-600', 'text-white', 'border-red-600');
                } else {
                    button.classList.remove('bg-red-600', 'text-white', 'border-red-600');
                    button.classList.add('bg-gray-200', 'text-gray-800', 'border-gray-300');
                }
            });
        });
    </script>
@endsection