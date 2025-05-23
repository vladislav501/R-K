@extends('layouts.app')

@section('title', 'Панель менеджера')

@section('content')
    <h1>Панель менеджера: {{ $store->name }}</h1>
    <p>Адрес: {{ $store->address }}</p>
    <div>
        <h2>Статистика</h2>
        <p>Активные заказы: {{ $activeOrders }}</p>
        <p>Завершённые заказы: {{ $completedOrders }}</p>
        <p>Ожидающие поставки: {{ $pendingSupplies }}</p>
    </div>
    <div>
        <h2>Быстрые действия</h2>
        <a href="{{ route('manager.orders.index') }}">Просмотреть заказы</a>
        <a href="{{ route('manager.products.index') }}">Управление товарами</a>
        <a href="{{ route('manager.supplies.index') }}">Поставки</a>
    </div>
@endsection