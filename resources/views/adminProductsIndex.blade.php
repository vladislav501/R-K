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
        <a href="{{ route('admin.products.create') }}" class="products-create-button">Добавить товар</a>
        @if(session('success'))
            <div class="products-success-notice">{{ session('success') }}</div>
        @endif
        @if($products->isEmpty())
            <p class="products-empty-text">Нет товаров для отображения.</p>
        @else
            <table class="products-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Изображение</th>
                        <th>Название</th>
                        <th>Цена</th>
                        <th>Бренд</th>
                        <th>Категория</th>
                        <th>В наличии</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                @if($product->image_1)
                                    <img src="{{ Storage::url($product->image_1) }}" alt="{{ $product->name }}" class="products-table-image">
                                @else
                                    Нет изображения
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>BYR {{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->brand->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->is_available ? 'Да' : 'Нет' }}</td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}" class="products-edit-button">Редактировать</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="products-delete-form" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="products-delete-button" onclick="return confirm('Удалить товар?')">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection