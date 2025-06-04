@extends('layouts.app')

@section('title', 'Управление пунктами выдачи')

@section('content')
<div class="pickup-point-manage-wrapper">
    <div class="pickup-point-nav-panel">
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
    <div class="pickup-point-main-content">
        <h1>Управление пунктами выдачи</h1>
        <a href="{{ route('admin.pickup_points.create') }}" class="pickup-point-add-link">Добавить пункт выдачи</a>
        @if(session('success'))
            <div class="pickup-point-success-notice">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="pickup-point-error-notice">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif
        <div class="pickup-point-list-container">
            @forelse($pickupPoints as $point)
                <div class="pickup-point-item-block">
                    <p>{{ $point->name }} - {{ $point->address }} ({{ $point->hours }})</p>
                    <a href="{{ route('admin.pickup_points.edit', $point) }}">Редактировать</a>
                    <form action="{{ route('admin.pickup_points.destroy', $point) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Вы уверены, что хотите удалить пункт выдачи?')">Удалить</button>
                    </form>
                </div>
            @empty
                <p class="pickup-point-empty-text">Пунктов выдачи нет.</p>
            @endforelse
        </div>
        <a href="{{ route('admin.index') }}" class="pickup-point-back-link">Назад</a>
    </div>
</div>
@endsection