@extends('layouts.app')

@section('title', 'Поставки')

@section('content')
    <h1>Поставки для {{ $store->name }}</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($supplies->isEmpty())
        <p>Нет активных поставок.</p>
    @else
        <table class="table-auto w-full">
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
                                {{ $item->product->name }} ({{ $item->color->name }}, {{ $item->size->name }}, {{ $item->quantity }} шт.)<br>
                            @endforeach
                        </td>
                        <td>{{ $supply->status }}</td>
                        <td>
                            <a href="{{ route('manager.supply.check', $supply) }}" class="btn btn-primary">Проверить</a>
                            <a href="{{ route('manager.supplies.download', $supply) }}" class="btn btn-secondary">Скачать список</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <a href="/manager/supplies/archive" class="btn btn-secondary">Архив поставок</a>
    <button class="back-to-top">
        <i class="fi fi-rr-arrow-small-up"></i>
    </button>
    <script>
        document.querySelector('.back-to-top').addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
@endsection