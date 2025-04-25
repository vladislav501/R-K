@extends('layouts.asset')

@section('content')
    <div class="add-product-content">
        <h1 class="add-product-title">Добавление</h1>
        <div class="add-product-menus-container">
            <div class="add-product-left-menu">
                <button class="add-product-left-menu-btn">
                    <a href="{{ route('addProduct.index') }}">Товар</a>
                </button>
                <button class="add-product-left-menu-btn">
                    <a href="{{ route('addBrand.index') }}">Бренд одежды</a>
                </button>
                <button class="add-product-left-menu-btn">
                    <a href="{{ route('addCollection.index') }}">Коллекция одежды</a>
                </button>
            </div>
            <div class="add-product-right-menu">
                <form action="{{ route('product.store') }}" method="post" class="add-product-form" enctype="multipart/form-data">
                    @csrf
                    <div class="add-product-form-container">
                        <div class="add-product-photo-container add-product-form-group">
                            <label class="add-product-form-label">Фотографии товара</label>
                            <h2>Главная фотография:</h2>
                            <input type="file" name="previewImage" class="add-product-form-input" required>
                            <h3>Фотографии на странице товара:</h3>
                            <input type="file" name="image1" class="add-product-form-input" required>
                            <input type="file" name="image2" class="add-product-form-input" required>
                            <input type="file" name="image3" class="add-product-form-input" required>
                            <input type="file" name="image4" class="add-product-form-input" required>
                        </div>
                        <div class="add-product-form-group">
                            <label class="add-product-form-label">Бренд</label>
                            <select name="brandId" class="add-product-form-select" required>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="add-product-form-group">
                            <label class="add-product-form-label">Пол</label>
                            <select name="sex" class="add-product-form-select" required>
                                <option value="Мужской">Мужской</option>
                                <option value="Женский">Женский</option>
                                <option value="Унисекс">Унисекс</option>
                            </select>
                        </div>
                        <div class="add-product-form-group">
                            <label class="add-product-form-label">Тип одежды</label>
                            <select name="typeId" class="add-product-form-select" required>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="add-product-form-group">
                            <label class="add-product-form-label">Категории</label>
                            <select name="categoryId" class="add-product-form-select" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="add-product-form-group">
                            <label class="add-product-form-label">Коллекция</label>
                            <select name="collectionId" class="add-product-form-select" required>
                                @foreach($collections as $collection)
                                    <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="add-product-form-group">
                            <label class="add-product-form-label">Название товара</label>
                            <input name="title" class="add-product-form-input" placeholder="Название товара" required>
                        </div>
                        <div class="add-product-form-group">
                            <label class="add-product-form-label">Короткое название товара</label>
                            <input name="shortTitle" class="add-product-form-input" placeholder="Краткое название" required>
                        </div>
                        <div class="add-product-form-group">
                            <label class="add-product-form-label">Описание товара</label>
                            <textarea name="description" class="add-product-form-input" placeholder="Описание" required></textarea>
                        </div>
                        <div class="add-product-form-group">
                            <label class="add-product-form-label">Цвета</label>
                            <input name="color" class="add-product-form-input " placeholder="Цвет" required>
                        </div>
                        <div class="add-product-form-group">
                            <label class="add-product-form-label">Размеры</label>
                            <input name="size" class="add-product-form-input" placeholder="Размер" required>
                        </div>
                        <div class="add-product-form-group">
                            <label class="add-product-form-label">Цена</label>
                            <input name="price" type="number" step="0.01" class="add-product-form-input" required>
                        </div>
                        <div class="add-product-form-group">
                            <label class="add-product-form-label">Состав</label>
                            <textarea name="composition" class="add-product-form-input" placeholder="Состав" required></textarea>
                        </div>
                        <div class="add-product-form-group">
                            <label class="add-product-form-label">Страна разработки дизайна</label>
                            <input name="designCountry" class="add-product-form-input" required>
                        </div>
                        <div class="add-product-form-group">
                            <label class="add-product-form-label">Страна производства</label>
                            <input name="manufacturenCountry" class="add-product-form-input" required>
                        </div>
                        <div class="add-product-form-group">
                            <label class="add-product-form-label">Импортер</label>
                            <input name="importer" class="add-product-form-input" required>
                        </div>
                        <button type="submit" class="add-product-form-button">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
        <button class="add-product-form-button"><a href="{{ route('admin.index') }}">Назад</a></button>
    </div>
@endsection