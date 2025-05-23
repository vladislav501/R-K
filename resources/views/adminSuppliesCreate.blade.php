@extends('layouts.app')

@section('title', 'Создать поставку')

@section('content')
    <h1>Создать поставку</h1>
    <form method="POST" action="{{ route('admin.supplies.store.step1') }}">
        @csrf
        <div>
            <label>Выберите товары:</label>
            @foreach($products as $product)
                <label>
                    <input type="checkbox" name="products[]" value="{{ $product->id }}"> {{ $product->name }}
                </label>
            @endforeach
            @error('products')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <button type="submit">Далее</button>
    </form>
@endsection