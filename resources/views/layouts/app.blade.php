<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap');
    </style>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <header class="header-container">
        <div class="header-wrapper">
            <select class="header-store-select" onchange="window.location.href=this.value">
                <option value="{{ route('products.index') }}">Общий каталог</option>
                @foreach(\App\Models\Store::all() as $store)
                    <option value="{{ route('products.index', ['store_id' => $store->id]) }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                @endforeach
            </select>
            <a href="{{ route('products.index') }}" class="header-logo">Luxury Comfort</a>
            <form action="#" method="GET" class="header-search-form">
                <input type="text" name="query" placeholder="Поиск товаров..." class="header-search-input" required>
                <button type="submit" class="header-search-button">Поиск</button>
            </form>
            <nav class="header-nav">
                <a href="{{ route('products.index') }}" class="header-nav-link">Каталог</a>
                @auth
                    <a href="{{ route('cart.index') }}" class="header-nav-link">Корзина</a>
                    <a href="{{ route('profile.index') }}" class="header-nav-link">Профиль</a>
                    @can('is-admin')
                        <a href="{{ route('admin.index') }}" class="header-nav-link">Админ-панель</a>
                    @endcan
                    @can('is-manager')
                        <a href="{{ route('manager.index') }}" class="header-nav-link">Менеджер-панель</a>
                    @endcan
                    <form action="{{ route('logout') }}" method="POST" class="header-logout-form">
                        @csrf
                        <button type="submit" class="header-nav-link header-logout-button">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="header-nav-link">Войти</a>
                    <a href="{{ route('register') }}" class="header-nav-link">Регистрация</a>
                @endauth
            </nav>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>