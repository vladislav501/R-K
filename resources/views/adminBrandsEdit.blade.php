@extends('layouts.app')

@section('title', 'Редактировать бренд')

@section('content')
    <div class="brands-edit">
        <div class="brands-edit-sidebar">
            <h2>Админ Панель</h2>
            <ul>
                <li><a href="{{ route('admin.products.index') }}">Управление товарами</a></li>
                <li><a href="{{ route('admin.brands.index') }}">Управление брендами</a></li>
                <li><a href="{{ route('admin.categories.index') }}">Управление категориями</a></li>
                <li><a href="">Управление типами одежды</a></li>
                <li><a href="{{ route('admin.collections.index') }}">Управление коллекциями</a></li>
                <li><a href="{{ route('admin.colors.index') }}">Управление цветами</a></li>
                <li><a href="{{ route('admin.sizes.index') }}">Управление размерами</a></li>
                <li><a href="">Управление пунктами выдачи</a></li>
                <li><a href="">Управление поставками</a></li>
                <li><a href="">Архив поставок</a></li>
                <li><a href="">Управление заказами</a></li>
            </ul>
        </div>
        <div class="brands-edit-content">
            <h1>Редактировать бренд</h1>
            @if(session('success'))
                <div class="brands-alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="brands-alert-danger">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif
            <div class="brands-edit-form">
                <form method="POST" action="{{ route('admin.brands.update', $brand) }}">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name">Название:</label>
                        <input type="text" name="name" id="name" value="{{ $brand->name }}" required>
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit">Сохранить</button>
                </form>
            </div>
            <a href="{{ route('admin.brands.index') }}" class="brands-edit-back">Назад</a>
        </div>
    </div>
@endsection