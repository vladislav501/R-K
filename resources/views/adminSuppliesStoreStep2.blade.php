@extends('layouts.app')

@section('title', 'Создать поставку - Шаг 2')

@section('content')
    <h1>Создать поставку - Шаг 2</h1>
    <form method="POST" action="{{ route('admin.supplies.store.step2') }}">
        @csrf
        <div>
            <label for="store_id">Пункт выдачи:</label>
            <select name="store_id" id="store_id" required>
                @foreach(\App\Models\Store::all() as $store)
                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                @endforeach
            </select>
            @error('store_id')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label>Товары и количество:</label>
            @foreach($selectedProducts as $product)
                <div>
                    <input type="hidden" name="products[]" value="{{ $product->id }}">
                    <label>{{ $product->name }}:
                        <input type="number" name="quantities[]" min="1" required>
                    </label>
                </div>
            @endforeach
            @error('quantities')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <button type="submit">Отправить поставку</button>
    </form>
@endsection