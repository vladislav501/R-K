@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<div class="home-page-wrapper">
    <section class="home-hero">
        <div class="home-hero-content">
            <h1>Стиль, вдохновляющий на великое</h1>
            <p>Погрузитесь в мир моды с нашими эксклюзивными коллекциями, созданными для тех, кто стремится к уникальности. От изысканных силуэтов до смелых акцентов — найдите свой идеальный образ.</p>
            <a href="{{ route('products.index') }}" class="home-hero-cta">Открыть каталог</a>
        </div>
        <img src="{{ asset('images/bannerHome.webp') }}" class="home-hero-image">
    </section>

    <section class="home-about-brands">
        <div class="home-about-brands-content">
            <h2>Иконы стиля</h2>
            <p>Мировые знаменитости задают тренды, вдохновляя нас на эксперименты с образом. От красных ковровых дорожек до уличного стиля – их выбор в моде становится эталоном вкуса. Исследуйте коллекции, созданные по мотивам культовых образов, и найдите свой стиль.</p>
            <a href="{{ route('products.index') }}" class="home-brand-cta">Подробнее</a>
        </div>
        <img src="{{ asset('images/bannerVip.webp') }}" class="home-hero-image">
    </section>

    <section class="home-new-arrivals">
        <h2>Новинки каталога</h2>
        <div class="home-carousel-wrapper">
            <button class="carousel-prev" aria-label="Предыдущий слайд">❮</button>
            <div class="home-carousel">
                @foreach($newProducts->take(9) as $product)
                    <div class="home-product-card">
                        <a href="{{ route('products.show', $product) }}" class="home-product-link">
                            <div class="home-product-image">
                                @if($product->image_1)
                                    <img src="{{ Storage::url($product->image_1) }}" alt="{{ $product->name }}" class="home-product-image-img">
                                @else
                                    <div class="home-product-image-placeholder">Нет изображения</div>
                                @endif
                            </div>
                            <div class="home-product-info">
                                <h3>{{ $product->name }}</h3>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <button class="carousel-next" aria-label="Следующий слайд">❯</button>
        </div>
        <div class="carousel-dots">
            <span class="carousel-dot active" data-slide="0"></span>
            <span class="carousel-dot" data-slide="1"></span>
            <span class="carousel-dot" data-slide="2"></span>
        </div>
        <a href="{{ route('products.index') }}" class="home-section-link">Посмотреть все новинки</a>
    </section>

    <section class="home-promo-banner">
        <div class="home-promo-item">
            <img src="{{ asset('images/summerHome.webp') }}" class="home-promo-image">
            <div class="home-promo-text">
                <h3>Летняя коллекция</h3>
                <p>Яркие краски, легкие ткани и смелые решения для вашего идеального лета. Создайте образ, который запомнится!</p>
                <a href="{{ route('products.index') }}" class="home-promo-link">Подробнее</a>
            </div>
        </div>
    </section>

    <section class="home-promo-grid">
        <div class="home-promo-small">
            <img src="{{ asset('images/accesoryHome.webp') }}" class="home-promo-small-image">
            <div class="home-promo-text-small">
                <h3>Аксессуары</h3>
                <p>Дополните ваш стиль с нашими уникальными аксессуарами от культовых брендов.</p>
                <a href="{{ route('products.index') }}" class="home-promo-link-small">Подробнее</a>
            </div>
        </div>
        <div class="home-promo-small">
            <img src="{{ asset('images/shoesHome.webp') }}" class="home-promo-small-image">
            <div class="home-promo-text-small">
                <h3>Обувь</h3>
                <p>Комфорт и стиль в каждой паре — от кроссовок до элегантных туфель.</p>
                <a href="{{ route('products.index') }}" class="home-promo-link-small">Подробнее</a>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.querySelector('.home-carousel');
    const prevButton = document.querySelector('.carousel-prev');
    const nextButton = document.querySelector('.carousel-next');
    const dots = document.querySelectorAll('.carousel-dot');
    let currentSlide = 0;
    const totalSlides = 3;
    const slideWidth = carousel.querySelector('.home-product-card').offsetWidth + 20;
    let autoSlideInterval;

    function updateCarousel() {
        carousel.style.transform = `translateX(-${currentSlide * slideWidth * 3}px)`;
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlide);
        });
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateCarousel();
        }, 10000);
    }

    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    prevButton.addEventListener('click', () => {
        stopAutoSlide();
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        updateCarousel();
        startAutoSlide();
    });

    nextButton.addEventListener('click', () => {
        stopAutoSlide();
        currentSlide = (currentSlide + 1) % totalSlides;
        updateCarousel();
        startAutoSlide();
    });

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            stopAutoSlide();
            currentSlide = parseInt(dot.dataset.slide);
            updateCarousel();
            startAutoSlide();
        });
    });

    startAutoSlide();
});
</script>
@endsection