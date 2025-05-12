@extends('layouts.asset')
@section('content')
    <div class="content">
        <h1 class="contentTitle">Изменить бренд</h1>
        <form action="{{ route('brand.update', $brand->id) }}" method="post" class="addBrandForm">
            @csrf
            @method('patch')
            <input name="name" value="{{ $brand->name }}">
            <button type="submit">Изменить</button>
        </form>
        <button><a href="{{ route('brands.index') }}">Назад</a></button>
    </div>
@endsection