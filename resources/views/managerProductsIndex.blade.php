@extends('layouts.app')

@section('title', 'Товары')

@section('content')
    <div class="products-manager">
        <div class="products-manager-sidebar">
            <h2>Менеджер Панель</h2>
            <ul>
                <li><a href="{{ route('manager.orders.index') }}">Заказы</a></li>
                <li><a href="{{ route('manager.orders.archive') }}">Архив заказов</a></li>
                <li><a href="{{ route('manager.products.index') }}">Товары</a></li>
            </ul>
        </div>
        <div class="products-manager-content">
            <h1>Товары в {{ $store->name }}</h1>
            @if($products->isEmpty())
                <div class="products-manager-empty">Нет товаров.</div>
            @else
                <div class="products-manager-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Название</th>
                                <th>Цена</th>
                                <th>Количество</th>
                                <th>В наличии</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->price }} BYN</td>
                                    <td>{{ $product->pivot->quantity }}</td>
                                    <td>{{ $product->is_available ? 'Да' : 'Нет' }}</td>
                                    <td>
                                        <form action="#" method="POST">
                                            @csrf
                                            <input type="number" name="quantity" value="{{ $product->pivot->quantity }}" min="0" required>
                                            <label>
                                                <input type="checkbox" name="is_available" value="1" {{ $product->is_available ? 'checked' : '' }}> В наличии
                                                <input type="hidden" name="is_available" value="0">
                                            </label>
                                            <button type="submit">Обновить</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <button class="products-manager-back-to-top">
                <i class="fi fi-rr-arrow-small-up"></i>
            </button>
        </div>
    </div>
    <script>
        document.querySelector('.products-manager-back-to-top').addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
@endsection