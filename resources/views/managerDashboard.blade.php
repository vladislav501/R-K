@extends('layouts.app')

@section('title', 'Панель менеджера')

@section('content')
    <div class="manager-panel">
        <div class="sidebar">
            <h2>Менеджер Панель</h2>
            <ul>
                <li><a href="{{ route('manager.orders.index') }}">Просмотреть заказы</a></li>
                <li><a href="{{ route('manager.products.index') }}">Управление товарами</a></li>
                <li><a href="{{ route('manager.supplies.index') }}">Поставки</a></li>
                <li><a href="{{ route('manager.supplies.archive') }}">Архив поставок</a></li>
                <li><a href="{{ route('manager.orders.archive') }}">Архив заказов</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Панель менеджера: {{ $store->name }}</h1>
            <p class="store-info"><strong>Адрес:</strong> {{ $store->address }}</p>
            <div class="stats-section">
                <h2>Статистика</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <svg class="card-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                        <p><strong>Активные заказы:</strong> {{ $activeOrders }}</p>
                    </div>
                    <div class="stat-card">
                        <svg class="card-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                        </svg>
                        <p><strong>Завершённые заказы:</strong> {{ $completedOrders }}</p>
                    </div>
                    <div class="stat-card">
                        <svg class="card-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M19.5 8H17V6c0-1.1-.9-2-2-2H9c-1.1 0-2 .9-2 2v2H4.5c-.83 0-1.5.67-1.5 1.5V12c0 .83.67 1.5 1.5 1.5H7v2c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2v-2h2.5c.83 0 1.5-.67 1.5-1.5V9.5c0-.83-.67-1.5-1.5-1.5zM16 12H8V9h8v3z"/>
                        </svg>
                        <p><strong>Ожидающие поставки:</strong> {{ $pendingSupplies }}</p>
                    </div>
                </div>
            </div>
            <div class="actions-section">
                <h2>Быстрые действия</h2>
                <div class="action-buttons">
                    <a href="{{ route('manager.orders.index') }}" class="action-button">Просмотреть заказы</a>
                    <a href="{{ route('manager.products.index') }}" class="action-button">Управление товарами</a>
                    <a href="/manager/supplies" class="action-button">Поставки</a>
                </div>
            </div>
        </div>
    </div>
@endsection