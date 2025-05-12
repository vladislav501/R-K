@extends('layouts.asset')
@section('content')
    <div class="content">
        <h1 class="contentTitle">Добавить новый бренд</h1>
        <form action="{{ route('brand.store') }}" method="post" class="addBrandForm">
            @csrf
            <input name="name">
            <input name="productId">
            <button type="submit">Добавить</button>
        </form>
        <button><a href="{{ route('admin.index') }}">Назад</a></button>
    </div>
@endsection