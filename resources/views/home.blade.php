@extends('layouts.asset')
@section('content')
    <div class="contentHome">
        <div class="saleContainer">
            <a href="{{ route('sale.index') }}">
                <img src="{{ asset('images/saleImage.webp') }}" alt="saleImage" class="homeBlockImage">
                <div class="homeBlockControls">
                    <h1 class="homeBlockTitle">Распродажа</h1>
                    <p class="homeBlockText">
                        Пора обновить гардероб! Максимальные скидки на модели мировых брендов.
                        В распродаже участвуют: Brunello Cucinelli, Dolce & Gabbana, Max Mara, Missoni, BOSS, Emporio
                        Armani, Peserico, Santoni, Herno, ZEGNA, ZILLI, Stefano Ricci, Etro и многих других
                    </p>
                    <button class="homeBlockButton">
                        <a href="{{ route('sale.index') }}">Подробнее</a>
                    </button>
                </div>
            </a>
        </div>
        
        <div class="forSheContainer">
            <h1 class="homeBlockTitle">Новые послупления для неё</h1>
            <div class="imageGallery">
                <a href="{{ route('womans.index') }}">
                    <img src="{{ asset('images/1.jpg') }}" alt="img1" class="imageGalleryImg">
                </a>
                <a href="{{ route('womans.index') }}">
                    <img src="{{ asset('images/2.jpg') }}" alt="img2" class="imageGalleryImg">
                </a>
                <a href="{{ route('womans.index') }}">
                    <img src="{{ asset('images/3.jpg') }}" alt="img3" class="imageGalleryImg">
                </a>
                <a href="{{ route('womans.index') }}">
                    <img src="{{ asset('images/4.jpg') }}" alt="img4" class="imageGalleryImg">
                </a>
                <a href="{{ route('womans.index') }}">
                    <img src="{{ asset('images/5.jpg') }}" alt="img5" class="imageGalleryImg">
                </a>
            </div>
        </div>
    
        <div class="forHeContainer">
            <h1 class="homeBlockTitle">Новые послупления для него</h1>
            <div class="imageGallery">
                <a href="{{ route('womans.index') }}">
                    <img src="{{ asset('images/001.jpg') }}" alt="img1" class="imageGalleryImg">
                </a>
                <a href="{{ route('womans.index') }}">
                    <img src="{{ asset('images/002.jpg') }}" alt="img2" class="imageGalleryImg">
                </a>
                <a href="{{ route('womans.index') }}">
                    <img src="{{ asset('images/003.jpg') }}" alt="img3" class="imageGalleryImg">
                </a>
                <a href="{{ route('womans.index') }}">
                    <img src="{{ asset('images/004.jpg') }}" alt="img4" class="imageGalleryImg">
                </a>
                <a href="{{ route('womans.index') }}">
                    <img src="{{ asset('images/005.jpg') }}" alt="img5" class="imageGalleryImg">
                </a>
            </div>
        </div>
        
        <div class="celebritiesContainer">
            <a href="{{ route('products.index') }}">
                <img src="{{ asset('images/celebritiesImage.webp') }}" alt="celebritiesImage" class="homeBlockImage">
                <div class="homeBlockControls">
                    <h1 class="homeBlockTitle">Выбор знаменитостей</h1>
                    <p class="homeBlockText">
                        Образы итальянского бренда на красную дорожку выбирают Ума Турман, Анджелина Джоли и
                        Белла Хадид. Откройте для себя оду женственности, воплощенную в выверенных силуэтах, 
                        премиальных материалах и изысканном дизайне
                    </p>
                    <button class="homeBlockButton">
                        <a href="{{ route('products.index') }}">Подробнее</a>
                    </button>
                </div>
            </a>
        </div>
    
        <div class="accessoryContainer">
            <h1 class="homeBlockTitle">Аксесуары</h1>
            <div class="imageGallery">
                <a href="{{ route('womans.index') }}">
                    <img src="{{ asset('images/101.jpg') }}" alt="img1" class="imageGalleryImg">
                </a>
                <a href="{{ route('womans.index') }}">
                    <img src="{{ asset('images/102.jpg') }}" alt="img2" class="imageGalleryImg">
                </a>
                <a href="{{ route('womans.index') }}">
                    <img src="{{ asset('images/103.jpg') }}" alt="img3" class="imageGalleryImg">
                </a>
                <a href="{{ route('womans.index') }}">
                    <img src="{{ asset('images/104.jpg') }}" alt="img4" class="imageGalleryImg">
                </a>
                <a href="{{ route('womans.index') }}">
                    <img src="{{ asset('images/105.jpg') }}" alt="img5" class="imageGalleryImg">
                </a>
            </div>
        </div>
    </div>
@endsection