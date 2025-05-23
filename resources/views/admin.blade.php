@extends('layouts.app')

@section('title', 'Панель администратора')

@section('content')
    <h1>Панель администратора</h1>
    <ul>
        {{-- <li><a href="{{ route('admin.products.index') }}">Управление товарами</a></li>
        <li><a href="{{ route('admin.brands.index') }}">Управление брендами</a></li>
        <li><a href="{{ route('admin.categories.index') }}">Управление категориями</a></li>
        <li><a href="{{ route('admin.clothing-types.index') }}">Управление типами одежды</a></li>
        <li><a href="{{ route('admin.collections.index') }}">Управление коллекциями</a></li>
        <li><a href="{{ route('admin.colors.index') }}">Управление цветами</a></li>
        <li><a href="{{ route('admin.sizes.index') }}">Управление размерами</a></li>
        <li><a href="{{ route('admin.stores.index') }}">Управление пунктами выдачи</a></li>
        <li><a href="{{ route('admin.supplies.index') }}">Управление поставками</a></li>
        <li><a href="{{ route('admin.supplies.archive') }}">Архив поставок</a></li>
        <li><a href="{{ route('admin.orders.index') }}">Управление заказами</a></li> --}}
    </ul>
@endsection