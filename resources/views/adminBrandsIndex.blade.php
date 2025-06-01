@extends('layouts.app')

@section('title', 'Управление брендами')

@section('content')
    <div class="brands-index">
        <div class="brands-index-sidebar">
            <h2>Админ Панель</h2>
            <ul>
                <li><a href="{{ route('admin.products.index') }}">Управление товарами</a></li>
                <li><a href="{{ route('admin.brands.index') }}">Управление брендами</a></li>
                <li><a href="{{ route('admin.categories.index') }}">Управление категориями</a></li>
                <li><a href="">Управление типами одежды</a></li>
                <li><a href="{{ route('admin.collections.index') }}">Управление коллекциями</a></li>
                <li><a href="{{ route('admin.colors.index') }}">Управление цветами</a></li>
                <li><a href="{{ route('admin.sizes.index') }}">Управление размерами</a></li>
                <li><a href="">Управление пунктами выдачи</a></li>
                <li><a href="">Управление поставками</a></li>
                <li><a href="">Архив поставок</a></li>
                <li><a href="">Управление заказами</a></li>
            </ul>
        </div>
        <div class="brands-index-content">
            <h1>Управление брендами</h1>
            <a href="{{ route('admin.brands.create') }}" class="brands-index-add">Добавить бренд</a>
            @if(session('success'))
                <div class="brands-index-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="brands-index-error">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif
            <div class="brands-index-table">
                <table>
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $brand)
                            <tr>
                                <td>{{ $brand->name }}</td>
                                <td>
                                    <a href="{{ route('admin.brands.edit', $brand) }}">Редактировать</a>
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="brands-index-empty" colspan="2">Брендов нет.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <a href="{{ route('admin.index') }}" class="brands-index-back">Назад</a>
        </div>
    </div>
</body>
@endsection