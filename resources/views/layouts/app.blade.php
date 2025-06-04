<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Comfort</title>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-brands/css/uicons-brands.css'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap');
    </style>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
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
    <footer class="footer-container">
        <div class="footer-wrapper">
            <div class="footer-column">
                <h3>Категории</h3>
                <ul>
                    
                </ul>
            </div>
            <div class="footer-column">
                <h3>Для покупателей</h3>
                <ul>
                    <li><a href="/delivery">Варианты доставки</a></li>
                    <li><a href="/payment">Способы оплаты</a></li>
                    <li><a href="/pickup">Самовывоз</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Информация</h3>
                <ul>
                    <li><a href="/about">О нас</a></li>
                    <li><a href="/stores">Адреса магазинов</a></li>
                    <li><a href="/contact">Контакты</a></li>
                    <li><a href="/privacy">Политика конфиденциальности</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Следите за нами</h3>
                <div class="footer-social-icons">
                    <a href="https://vk.com" target="_blank">
                        <div class="footer-social-icon">
                            <i class="fi fi-brands-vk"></i>
                        </div>
                    </a>
                    <a href="https://telegram.org" target="_blank">
                        <div class="footer-social-icon">
                            <i class="fi fi-brands-telegram"></i>
                        </div>
                    </a>
                    <a href="https://instagram.com" target="_blank">
                        <div class="footer-social-icon">
                            <i class="fi fi-brands-instagram"></i>
                        </div>
                    </a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 LuxuryComfort. Все права защищены. <a href="/privacy">Политика конфиденциальности</a></p>
            </div>
        </div>
    </footer>
</body>
</html>