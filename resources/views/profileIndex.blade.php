@extends('layouts.app')

@section('title', 'Профиль')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Профиль</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <h2 class="text-xl font-semibold mb-2">Личные данные</h2>
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium">Имя</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" class="border p-2 w-full" required>
                @error('first_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium">Фамилия</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" class="border p-2 w-full" required>
                @error('last_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="middle_name" class="block text-sm font-medium">Отчество</label>
                <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $user->middle_name) }}" class="border p-2 w-full">
                @error('middle_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="border p-2 w-full" required>
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="delivery_address" class="block text-sm font-medium">Адрес доставки</label>
                <input type="text" name="delivery_address" id="delivery_address" value="{{ old('delivery_address', $user->delivery_address) }}" class="border p-2 w-full">
                @error('delivery_address')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium">Новый пароль</label>
                <input type="password" name="password" id="password" class="border p-2 w-full">
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium">Подтверждение пароля</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="border p-2 w-full">
            </div>
            <button type="submit" class="btn btn-primary">Обновить профиль</button>
        </form>

        <h2 class="text-xl font-semibold mt-6 mb-2">Ваши заказы</h2>
        @if($orders->isEmpty())
            <p>У вас нет заказов.</p>
        @else
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th>Номер заказа</th>
                        <th>Товары</th>
                        <th>Дата заказа</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>
                                @foreach($order->items as $item)
                                    {{ $item->product->name }} ({{ $item->quantity }} шт.)<br>
                                @endforeach
                            </td>
                            <td>{{ $order->order_date ? $order->order_date->format('d.m.Y H:i') : 'Не указана' }}</td>
                            <td>{{ $order->status }} (Raw: {{ $order->getRawOriginal('status') }})</td>
                            <td>
                                @if(in_array($order->status, ['assembled', 'ready_for_pickup', 'handed_to_courier']))
                                    <form action="{{ route('orders.confirm', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Подтвердить получение</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection