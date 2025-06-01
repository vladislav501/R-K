@extends('layouts.app')

@section('title', 'Архив поставок')

@section('content')
    <h1>Архив поставок для {{ $store->name }}</h1>
    @if($supplies->isEmpty())
        <p>Архив пуст.</p>
    @else
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
                        <td>{{ $supply->product->name }}</td>
                        <td>{{ $supply->quantity }}</td>
                        <td>{{ $supply->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <a href="/manager/supplies" class="btn btn-secondary">Назад</a>
@endsection