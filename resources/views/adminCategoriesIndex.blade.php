@extends('layouts.app')

@section('title', 'Управление категориями')

@section('content')
<div class="category-manage-wrapper">
    <div class="category-nav-panel">
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
        </ul>
    </div>
    <div class="category-main-content">
        <h1>Управление категориями</h1>
        <a href="{{ route('admin.categories.create') }}" class="category-add-link">Добавить категорию</a>
        @if(session('success'))
            <div class="category-success-notice">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="category-error-notice">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif
        <div class="category-list-container">
            @forelse($categories as $category)
                <div class="category-item-block">
                    <p>{{ $category->name }}</p>
                    <form action="{{ route('admin.categories.toggle', $category) }}" method="POST" class="category-toggle-form">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_active" value="0">
                        <label>
                            <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} onchange="this.form.submit()">
                            Активна
                        </label>
                    </form>
                    <a href="{{ route('admin.categories.edit', $category) }}">Редактировать</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Вы уверены, что хотите удалить категорию?')">Удалить</button>
                    </form>
                </div>
            @empty
                <p class="category-empty-text">Категорий нет.</p>
            @endforelse
        </div>
        <a href="{{ route('admin.index') }}" class="category-back-link">Назад</a>
    </div>
</div>
@endsection