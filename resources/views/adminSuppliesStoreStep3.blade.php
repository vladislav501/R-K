@extends('layouts.app')

@section('title', 'Создать поставку - Шаг 3')

@section('content')
    <h1>Создать поставку - Шаг 3: Указать количество</h1>
    <form method="POST" action="{{ route('admin.supplies.store.step3') }}">
        @csrf
        @foreach($selectedProducts as $product)
            <h2>{{ $product->name }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>Цвет</th>
                        <th>Размер</th>
                        <th>Количество</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($validated['colors'][$product->id] as $colorId)
                        @foreach($validated['sizes'][$product->id] as $sizeId)
                            @php
                                $color = $product->colors->find($colorId);
                                $size = $product->sizes->find($sizeId);
                            @endphp
                            <tr>
                                <td>{{ $color->name }}</td>
                                <td>{{ $size->name }}</td>
                                <td>
                                    <input type="number" name="quantities[{{ $product->id }}][{{ $colorId }}][{{ $sizeId }}]" min="1" required>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endforeach
        <button type="submit">Отправить поставку</button>
    </form>
    <a href="{{ route('admin.supplies.create') }}">Назад</a>
@endsection