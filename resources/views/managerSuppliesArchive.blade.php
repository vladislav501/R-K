@extends('layouts.app')

@section('title', 'Архив поставок')

@section('content')
    <div class="supplies-archive">
        <div class="supplies-archive-sidebar">
            <h2>Менеджер Панель</h2>
            <ul>
                <li><a href="{{ route('manager.supplies.index') }}">Поставки</a></li>
                <li><a href="{{ route('manager.supplies.archive') }}">Архив поставок</a></li>
            </ul>
        </div>
        <div class="supplies-archive-content">
            <h1>Архив поставок для {{ $pickupPoint->name }}</h1>
            @if($supplies->isEmpty())
                <div class="supplies-archive-empty">Архив пуст.</div>
            @else
                <div class="supplies-archive-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Номер поставки</th>
                                <th>Товар</th>
                                <th>Количество</th>
                                <th>Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supplies as $supply)
                                <tr>
                                    <td>{{ $supply->id }}</td>
                                    <td>{{ $supply->items->first()->product->name ?? '—' }}</td>
                                    <td>
                                        Отправлено: {{ $supply->items->sum('quantity') }} шт.<br>
                                        Получено: {{ $supply->items->sum('received_quantity') }} шт.
                                    </td>
                                    <td>{{ $supply->status_label }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <a href="{{ route('manager.supplies.index') }}" class="supply-check-back">Вернуться к поставкам</a>
            <button class="supplies-archive-back-to-top">
                <i class="fi fi-rr-arrow-small-up"></i>
            </button>
        </div>
    </div>
    <script>
        document.querySelector('.supplies-archive-back-to-top').addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
@endsection