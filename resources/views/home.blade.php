@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<div class="home-page-wrapper">
    <div class="home-nav-panel">
        <h2>Навигация</h2>
        <ul>
            <li><a href="{{ route('home') }}">Главная</a></li>
            <li><a href="{{ route('products.index') }}">Каталог</a></li>
            <li><a href="{{ route('about') }}">О нас</a></li>
            <li><a href="{{ route('contact') }}">Контакты</a></li>
        </ul>
    </div>
    <div class="home-main-content">
        <section class="home-new-arrivals">
            <h1>Новые поступления для нее</h1>
            <div class="home-product-grid">
                <!-- Placeholder for dynamic products -->
                @for ($i = 1; $i <= 4; $i++)
                    <div class="home-product-item">
                        <img src="{{ asset('images/product-placeholder.jpg') }}" alt="Новинка">
                        <p>Товар {{ $i }}</p>
                        <span>BYR 99.99</span>
                        <a href="{{ route('products.index') }}" class="home-product-link">Купить</a>
                    </div>
                @endfor
            </div>
            <a href="{{ route('products.index') }}" class="home-section-link">Посмотреть все новинки</a>
        </section>

        <section class="home-brand-highlights">
            <h2>Новинки в брендах</h2>
            <div class="home-brand-grid">
                <!-- Placeholder for dynamic brands -->
                @for ($i = 1; $i <= 3; $i++)
                    <div class="home-brand-item">
                        <img src="{{ asset('https://imageproxy.fh.by/NhMPYPMzAMZ2nWlZliwjZSkBPwdsnL8U7L73YIX1I8A/w:2528/h:1010/rt:fill/q:95/czM6Ly9maC1wcm9kdWN0aW9uLXJmMy8xMjg2ODA1L2Jhbm5lci5wbmc.webp') }}" alt="Бренд">
                        <p>Бренд {{ $i }}</p>
                        <a href="{{ route('products.index') }}" class="home-brand-link">Каталог</a>
                    </div>
                @endfor
            </div>
        </section>

        <section class="home-promo-banner">
            <div class="home-promo-item">
                <img src="{{ asset('https://imageproxy.fh.by/zLvKcu-Qg_1993JjfeUs9e3YznQXmcM0Y52x84402Gg/w:2528/h:1010/rt:fill/q:95/czM6Ly9maC1wcm9kdWN0aW9uLXJmMy8xMjg2MjcxL2Jhbm5lci0zLnBuZw.webp') }}" alt="Vacation Vibes">
                <div class="home-promo-text">
                    <h3>Vacation Vibes</h3>
                    <p>Откройте для себя беззаботное настроение и непревзойденный комфорт образов, вдохновленных солнцем. Ощутите гармонию ярких красок и легких текстур, отражающих совершенство летних сочетаний для вашего незабываемого отпуска. Let’s get away!</p>
                    <a href="{{ route('products.index') }}" class="home-promo-link">Яркие акценты для запоминающихся образов</a>
                </div>
            </div>
        </section>

        <section class="home-promo-grid">
            <div class="home-promo-small">
                <img src="{{ asset('https://imageproxy.fh.by/sdhVanCY99xnzHDI9mPB_I4Gpj-0shRyFNLsYzPon4Q/w:1232/h:924/rt:fill/q:95/czM6Ly9maC1wcm9kdWN0aW9uLXJmMy8xMjg2NzkxLzYxNng0NjIucG5n.webp') }}" alt="Солнцезащитные очки">
                <div class="home-promo-text-small">
                    <h3>Лето — в каждом взгляде</h3>
                    <p>Выбирайте идеальные солнцезащитные очки для эффектного образа</p>
                    <a href="{{ route('products.index') }}" class="home-promo-link-small">Посмотреть</a>
                </div>
            </div>
            <div class="home-promo-small">
                <img src="{{ asset('https://imageproxy.fh.by/7deVAUQUbEqGhbbVzpVkN6DofS0L617Ju6WH3mYwlp0/w:1232/h:924/rt:fill/q:95/czM6Ly9maC1wcm9kdWN0aW9uLXJmMy8xMjg2NzkyLzYxNng0NjItMS5wbmc.webp') }}" alt="Спортивная обувь">
                <div class="home-promo-text-small">
                    <h3>Идеальны для весенних прогулок</h3>
                    <p>Дополните ваш образ стильной спортивной обувью от культовых брендов</p>
                    <a href="{{ route('products.index') }}" class="home-promo-link-small">Посмотреть</a>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection