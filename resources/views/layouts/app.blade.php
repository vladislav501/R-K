<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <a href="{{ route('products.index') }}">Каталог</a>
            @auth
                <a href="{{ route('cart.index') }}">Корзина</a>
                <a href="{{ route('profile.index') }}">Профиль</a>
                @can('is-admin')
                    <a href="{{ route('admin.index') }}">Админ-панель</a>
                @endcan
                @can('is-manager')
                    <a href="{{ route('manager.index') }}">Менеджер-панель</a>
                @endcan
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit">Выйти</button>
                </form>
            @else
                <a href="{{ route('login') }}">Войти</a>
                <a href="{{ route('register') }}">Регистрация</a>
            @endauth
            <select onchange="window.location.href=this.value">
                <option value="{{ route('products.index') }}">Общий каталог</option>
                @foreach(\App\Models\Store::all() as $store)
                    <option value="{{ route('products.index', ['store_id' => $store->id]) }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                @endforeach
            </select>
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>