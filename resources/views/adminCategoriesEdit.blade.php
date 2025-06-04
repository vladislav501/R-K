@extends('layouts.app')

@section('title', 'Редактировать категорию')

@section('content')
<div class="category-edit-wrapper">
    <div class="category-edit-nav">
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
    <div class="category-edit-content">
        <h1>Редактировать категорию</h1>
        @if(session('success'))
            <div class="category-edit-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="category-edit-error">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif
        <div class="category-edit-form">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                @csrf
                @method('PUT')
                <div>
                    <label for="name">Название:</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>
                    @error('name') <span class="category-edit-error-text">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="is_active">Активна:</label>
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                </div>
                <button type="submit">Сохранить</button>
            </form>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="category-edit-back">Назад</a>
    </div>
</div>
@endsection