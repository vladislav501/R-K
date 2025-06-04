@extends('layouts.app')

@section('title', 'Управление товарами')

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
        <h1>Управление товарами</h1>
        <a href="{{ route('admin.products.create') }}" class="products-add-button">Добавить товар</a>
        @if(session('success'))
            <div class="products-success-notice">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="products-error-notice">{{ session('error') }}</div>
        @endif
        <div class="products-filter-form">
            <form method="GET" action="{{ route('admin.products.index') }}">
                <div class="products-filter-group">
                    <label for="category_id">Категория:</label>
                    <select name="category_id" id="category_id">
                        <option value="">Все категории</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="products-filter-group">
                    <label for="brand_id">Бренд:</label>
                    <select name="brand_id" id="brand_id">
                        <option value="">Все бренды</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="products-filter-group">
                    <label for="collection_id">Коллекция:</label>
                    <select name="collection_id" id="collection_id">
                        <option value="">Все коллекции</option>
                        @foreach($collections as $collection)
                            <option value="{{ $collection->id }}" {{ request('collection_id') == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="products-filter-group">
                    <label for="color_id">Цвет:</label>
                    <select name="color_id" id="color_id">
                        <option value="">Все цвета</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" {{ request('color_id') == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="products-filter-group">
                    <label for="size_id">Размер:</label>
                    <select name="size_id" id="size_id">
                        <option value="">Все размеры</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}" {{ request('size_id') == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="products-filter-button">Фильтровать</button>
            </form>
        </div>
        <div class="products-table-container">
            @if($products->isEmpty())
                <p class="products-empty-text">Товаров нет.</p>
            @else
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Цена</th>
                            <th>Бренд</th>
                            <th>Категория</th>
                            <th>Коллекция</th>
                            <th>Тип одежды</th>
                            <th>Доступен</th>
                            <th>Цвета</th>
                            <th>Размеры</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>BYN {{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->brand->name }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td>{{ $product->collection ? $product->collection->name : 'Нет' }}</td>
                                <td>{{ $product->clothingType->name }}</td>
                                <td>{{ $product->is_available ? 'Да' : 'Нет' }}</td>
                                <td>{{ $product->colors->pluck('name')->join(', ') }}</td>
                                <td>{{ $product->sizes->pluck('name')->join(', ') }}</td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="products-edit-button">Редактировать</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="products-delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="products-delete-button" onclick="return confirm('Вы уверены, что хотите удалить товар?')">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <a href="{{ route('admin.index') }}" class="products-back-link">Назад</a>
    </div>
</div>
@endsection