<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Luxury Comfort')</title>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-brands/css/uicons-brands.css'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap');
    </style>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/categories.css') }}" rel="stylesheet">
    <link href="{{ asset('css/products.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <header class="header-container">
        <div class="header-wrapper">
            <a href="{{ route('home') }}" class="header-logo">Luxury Comfort</a>
            <button class="header-burger" aria-label="Toggle Menu" onclick="document.querySelector('.header-burger').classList.toggle('active'); document.querySelector('.header-collapsible').classList.toggle('active');">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="header-collapsible">
                <select class="header-store-select" onchange="window.location.href=this.value">
                    <option value="{{ route('products.index') }}" {{ !session('pickup_point_id') ? 'selected' : '' }}>Общий каталог</option>
                    @foreach(\App\Models\PickupPoint::all() as $pickupPoint)
                        <option value="{{ route('products.index', ['pickup_point_id' => $pickupPoint->id]) }}" {{ session('pickup_point_id') == $pickupPoint->id ? 'selected' : '' }}>{{ $pickupPoint->name }}</option>
                    @endforeach
                </select>
                <form action="{{ route('products.search') }}" method="GET" class="header-search-form">
                    <input type="text" name="query" placeholder="Поиск товаров..." class="header-search-input" value="{{ request('query') }}" required>
                    <input type="hidden" name="pickup_point_id" value="{{ session('pickup_point_id') }}">
                    <button type="submit" class="header-search-button">Поиск</button>
                </form>
                <nav class="header-nav">
                    <a href="{{ route('products.index', ['pickup_point_id' => session('pickup_point_id')]) }}" class="header-nav-link">Каталог</a>
                    @auth
                        <a href="{{ route('favorites.index') }}" class="header-nav-link">
                            Избранное
                            @php
                                $favoritesCount = Auth::user()->favorites()->count();
                            @endphp
                            @if($favoritesCount > 0)
                                <span class="header-badge">{{ $favoritesCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('cart.index') }}" class="header-nav-link">
                            Корзина
                            @php
                                $cartCount = Auth::user()->carts()->whereNull('order_id')->count();
                            @endphp
                            @if($cartCount > 0)
                                <span class="header-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('profile.index') }}" class="header-nav-link">
                            Профиль
                            @php
                                $readyOrdersCount = Auth::user()->orders()->where('status', 'ready_for_pickup')->count();
                            @endphp
                            @if($readyOrdersCount > 0)
                                <span class="header-badge">{{ $readyOrdersCount }}</span>
                            @endif
                        </a>
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
        </div>
    </header>
    <nav class="categories-nav">
        <div class="categories-wrapper">
            <ul class="categories-list">
                <li class="categories-item">
                    <a href="{{ route('products.index', ['pickup_point_id' => session('pickup_point_id')]) }}" class="categories-link {{ !request('category_id') && !Route::is('products.category') ? 'active' : '' }}">Все категории</a>
                </li>
                @foreach(\App\Models\Category::all() as $category)
                    <li class="categories-item">
                        <a href="{{ route('products.category', [$category, 'pickup_point_id' => session('pickup_point_id')]) }}" class="categories-link {{ Route::is('products.category') && request()->route('category')->id == $category->id ? 'active' : '' }}">{{ $category->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
    <footer class="footer-container">
        <div class="footer-wrapper">
            <div class="footer-column">
                <h3>Категории</h3>
                <ul>
                    @foreach(\App\Models\Category::all() as $category)
                        <li><a href="{{ route('products.category', [$category, 'pickup_point_id' => session('pickup_point_id')]) }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="footer-column">
                <h3>Для покупателей</h3>
                <ul>
                    <li><a href="{{ route('delivery') }}">Доставка</a></li>
                    <li><a href="{{ route('payment') }}">Способы оплаты</a></li>
                    <li><a href="{{ route('pickup') }}">Самовывоз</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Информация</h3>
                <ul>
                    <li><a href="{{ route('about') }}">О нас</a></li>
                    <li><a href="{{ route('stores') }}">Адреса магазинов</a></li>
                    <li><a href="{{ route('contact') }}">Контакты</a></li>
                    <li><a href="{{ route('privacy') }}">Политика конфиденциальности</a></li>
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
                <p>© 2025 LuxuryComfort. Все права защищены. <a href="{{ route('privacy') }}">Политика конфиденциальности</a></p>
            </div>
        </div>
    </footer>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>