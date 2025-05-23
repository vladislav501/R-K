@extends('layouts.app')

@section('title', 'Поставки')

@section('content')
    <h1>Поставки для {{ $store->name }}</h1>
    @if($supplies->isEmpty())
        <p>Нет активных поставок.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Номер поставки</th>
                    <th>Товар</th>
                    <th>Количество</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($supplies as $supply)
                    <tr>
                        <td>{{ $supply->id }}</td>
                        <td>{{ $supply->product->name }}</td>
                        <td>{{ $supply->quantity }}</td>
                        <td>{{ $supply->status }}</td>
                        <td>
                            <a href="{{ route('manager.supply.check', $supply) }}">Проверить</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <a href="{{ route('manager.supplies.archive') }}">Архив поставок</a>
@endsection