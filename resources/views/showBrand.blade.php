@extends('layouts.asset')
@section('content')
    <div class="content">
        <h1 class="contentTitle">{{ $brand->name }}</h1>
        <a href="{{ route('brands.index') }}">Назад</a>
        <a href="{{ route('brand.edit', $brand->id) }}">Изменить</a>
    </div>
    <form action="{{ route('brand.delete', $brand->id) }}" method="post">
        @csrf
        @method('delete')
        <button type="submit">Удалить</button>
    </form>
@endsection