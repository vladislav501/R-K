@extends('layouts.asset')
@section('content')
    <div class="content">
        <h1 class="contentTitle">Добавить новый товар</h1>
        <form action="{{ route('product.update', $product->id) }}" method="post" class="addProductForm">
            @csrf
            @method('patch')
            <select name="brandId" value="{{ $product->brandId }}">
                <option>1</option>
                <option>2</option>
                <option>3</option>
            </select>
            <select name="sex" value="{{ $product->sex }}">
                <option>Мужской</option>
                <option>Женский</option>
            </select>
            <select name="typeId" value="{{ $product->typeId }}">
                <option>1</option>
                <option>2</option>
                <option>3</option>
            </select>
            <select name="collectionId" value="{{ $product->collectionId }}">
                <option>1</option>
                <option>2</option>
                <option>3</option>
            </select>
            <select name="categoryId" value="{{ $product->categoryId }}">
                <option>1</option>
                <option>2</option>
                <option>3</option>
            </select>
            <input name="title" value="{{ $product->title }}">
            <input name="shortTitle" value="{{ $product->shortTitle }}">
            <textarea name="description">{{ $product->description }}</textarea>
            <select name="color" value="{{ $product->color }}">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                {{-- <option>🔴 Красный</option>
                <option>🟠 Оранжевый</option>
                <option>🟡 Жёлтый</option>
                <option>🟢 Зеленый</option>
                <option>🔵 Синий</option>
                <option>🟣 Фиолетовый</option>
                <option>🟤 Коричневый</option>
                <option>⚫ Чёрный</option> --}}
            </select>
            <select name="size" value="{{ $product->size }}">
                <option>1</option>
                <option>2</option>
                <option>3</option>
            </select>
            <input name="price" value="{{ $product->price }}">
            <input name="image" value="{{ $product->image }}">
            <textarea name="composition">{{ $product->composition }}</textarea>
            <input name="designCountry" value="{{ $product->designCountry }}">
            <input name="manufacturenCountry" value="{{ $product->manufacturenCountry }}">
            <input name="importer" value="{{ $product->importer }}">
            <input type="checkbox" name="availability" value="{{ $product->availability }}">
            <button type="submit">Добавить</button>
        </form>
        <button><a href="{{ route('admin.index') }}">Назад</a></button>
    </div>
@endsection