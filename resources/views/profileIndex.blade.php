@extends('layouts.app')

@section('title', 'Профиль')

@section('content')
    <div class="profile-page-container">
        @if(session('success'))
            <div class="profile-alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="profile-alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="profile-content-wrapper">
            <!-- Profile Card -->
            <div class="profile-card">
                <h2 class="profile-card-title">Личные данные</h2>
                <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
                    @csrf
                    @method('PUT')
                    <div class="profile-form-group">
                        <label for="first_name" class="profile-form-label">Имя</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" class="profile-input" required>
                        @error('first_name')
                            <span class="profile-form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="profile-form-group">
                        <label for="last_name" class="profile-form-label">Фамилия</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" class="profile-input" required>
                        @error('last_name')
                            <span class="profile-form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="profile-form-group">
                        <label for="middle_name" class="profile-form-label">Отчество</label>
                        <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $user->middle_name) }}" class="profile-input">
                        @error('middle_name')
                            <span class="profile-form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="profile-form-group">
                        <label for="email" class="profile-form-label">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="profile-input" required>
                        @error('email')
                            <span class="profile-form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="profile-form-group">
                        <label for="delivery_address" class="profile-form-label">Адрес доставки</label>
                        <input type="text" name="delivery_address" id="delivery_address" value="{{ old('delivery_address', $user->delivery_address) }}" class="profile-input">
                        @error('delivery_address')
                            <span class="profile-form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="button" class="profile-password-toggle">Изменить пароль</button>
                    <div class="profile-password-fields">
                        <div class="profile-form-group">
                            <label for="password" class="profile-form-label">Новый пароль</label>
                            <input type="password" name="password" id="password" class="profile-input">
                            @error('password')
                                <span class="profile-form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="profile-form-group">
                            <label for="password_confirmation" class="profile-form-label">Подтверждение пароля</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="profile-input">
                        </div>
                    </div>
                    <button type="submit" class="profile-submit-button">Обновить профиль</button>
                </form>
            </div>

            <!-- Orders List -->
            <div class="profile-orders-container">
                <h2 class="profile-orders-title">Ваши заказы</h2>
                @if($orders->isEmpty())
                    <p class="profile-no-orders">У вас нет заказов.</p>
                @else
                    <table class="profile-orders-table">
                        <thead>
                            <tr>
                                <th class="profile-table-header">Номер заказа</th>
                                <th class="profile-table-header">Товары</th>
                                <th class="profile-table-header">Дата заказа</th>
                                <th class="profile-table-header">Статус</th>
                                <th class="profile-table-header">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="profile-table-row">
                                    <td class="profile-table-cell">{{ $order->id }}</td>
                                    <td class="profile-table-cell">
                                        @foreach($order->items as $item)
                                            {{ $item->product->name }} ({{ $item->quantity }} шт.)<br>
                                        @endforeach
                                    </td>
                                    <td class="profile-table-cell">{{ $order->order_date ? $order->order_date->format('d.m.Y H:i') : 'Не указана' }}</td>
                                    <td class="profile-table-cell">{{ $order->status }} (Raw: {{ $order->getRawOriginal('status') }})</td>
                                    <td class="profile-table-cell">
                                        @if($order->status === 'ready_for_pickup')
                                            <form action="{{ route('orders.confirm', $order->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="profile-confirm-button">Подтвердить</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.querySelector('.profile-password-toggle');
            const passwordFields = document.querySelector('.profile-password-fields');

            if (toggleButton && passwordFields) {
                toggleButton.addEventListener('click', () => {
                    const isHidden = passwordFields.style.display === 'none' || !passwordFields.style.display;
                    passwordFields.style.display = isHidden ? 'flex' : 'none';
                    toggleButton.textContent = isHidden ? 'Скрыть изменение пароля' : 'Изменить пароль';
                });
            }
        });
    </script>
@endsection