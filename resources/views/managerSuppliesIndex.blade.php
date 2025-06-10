@extends('layouts.app')

@section('title', 'Поставки')

@section('content')
    <div class="supplies-manager">
        <div class="supplies-manager-sidebar">
            <h2>Менеджер Панель</h2>
            <ul>
                <li><a href="{{ route('manager.supplies.index') }}">Поставки</a></li>
                <li><a href="{{ route('manager.supplies.archive') }}">Архив поставок</a></li>
                <li><a href="{{ route('manager.products.index') }}">Товары</a></li>
            </ul>
        </div>
        <div class="supplies-manager-content">
            <h1>Поставки для {{ $store->name }}</h1>
            @if(session('success'))
                <div class="supplies-manager-success">{{ session('success') }}</div>
            @endif
            @if($supplies->isEmpty())
                <div class="supplies-manager-empty">Нет активных поставок.</div>
            @else
                <div class="supplies-manager-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Номер поставки</th>
                                <th>Товары</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supplies as $supply)
                                <tr>
                                    <td>{{ $supply->id }}</td>
                                    <td>
                                        @foreach($supply->items as $item)
                                            {{ $item->product->name }} ({{ $item->color ? $item->color->name : '-' }}, {{ $item->size ? $item->size->name : '-' }}, {{ $item->quantity }} шт.)<br>
                                        @endforeach
                                    </td>
                                    <td>{{ $supply->status }}</td>
                                    <td>
                                        <a href="{{ route('manager.supply.check', $supply) }}" class="supplies-manager-check">Проверить</a>
                                        <a href="{{ route('manager.supplies.download', $supply) }}" class="supplies-manager-download">Скачать список</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <a href="{{ route('manager.supplies.archive') }}" class="supplies-manager-archive">Архив поставок</a>
            <button class="supplies-manager-back-to-top">
                <i class="fi fi-rr-arrow-small-up"></i>
            </button>
        </div>
    </div>
    <script>
        document.querySelector('.supplies-manager-back-to-top').addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
@endsection