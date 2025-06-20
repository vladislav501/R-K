@extends('layouts.app')

@section('title', 'Управление поставками')

@section('content')
    <div class="supplies-index">
        <div class="supplies-index-sidebar">
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
        <div class="supplies-index-content">
            <h1>Управление поставками</h1>
            <a href="{{ route('admin.supplies.create') }}" class="supplies-index-add">Создать поставку</a>
            @if(session('success'))
                <div class="supplies-index-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="supplies-index-error">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif
            @forelse($supplies as $supply)
                <div class="supplies-index-item">
                    <p><strong>Поставка №:</strong> {{ $supply->id }}</p>
                    <p><strong>Пункт выдачи:</strong> {{ $supply->pickupPoint->name }}</p>
                    <p><strong>Статус:</strong> {{ $supply->status_label }}</p>
                    <div class="supplies-index-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Товар</th>
                                    <th>Цвет</th>
                                    <th>Размер</th>
                                    <th>Количество</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supply->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->color ? $item->color->name : '-' }}</td>
                                        <td>{{ $item->size ? $item->size->name : '-' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('admin.supplies.download', $supply) }}" class="supplies-index-download">Скачать список</a>
                </div>
            @empty
                <div class="supplies-index-empty">Поставок нет.</div>
            @endforelse
            <a href="{{ route('admin.supplies.archive') }}" class="supplies-index-archive">Архив поставок</a>
        </div>
    </div>
@endsection