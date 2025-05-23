@extends('layouts.app')

@section('title', 'Товары')

@section('content')
    <h1>Товары в {{ $store->name }}</h1>
    @if($products->isEmpty())
        <p>Нет товаров.</p>
    @else
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
                        <td>{{ $product->price }} ₽</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>{{ $product->is_available ? 'Да' : 'Нет' }}</td>
                        <td>
                            <form action="{{ route('manager.products.update', $product) }}" method="POST">
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
    @endif
@endsection