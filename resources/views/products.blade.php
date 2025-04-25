@extends('layouts.asset')
@section('content')
    <div class="content">
        <div class="productsPage">
            <div class="filters">
                <h2>Фильтры</h2>
                <div class="filterGroup">
                    <h3>Цена</h3>
                    <button class="filterButton">0 - 50 BYN</button>
                    <button class="filterButton">51 - 100 BYN</button>
                    <button class="filterButton">101 - 200 BYN</button>
                </div>
                <div class="filterGroup">
                    <h3>Категория</h3>
                    <button class="filterButton">Электроника</button>
                    <button class="filterButton">Одежда</button>
                    <button class="filterButton">Обувь</button>
                </div>
                <div class="filterGroup">
                    <h3>Бренд</h3>
                    <button class="filterButton">Nike</button>
                    <button class="filterButton">Adidas</button>
                    <button class="filterButton">Apple</button>
                </div>
            </div>
            <div class="products">
                @foreach ($products as $product)
                <div class="productsItem">
                    <div class="itemPhoto">
                        <a href="{{ route('product.show', $product->id) }}">
                            @if($product->images)
                                <img src="{{ Storage::url($product->images->previewImagePath) }}" alt="Preview Image" class="productItemImage">
                            @else
                                <img src="{{ asset('images/empty.svg') }}" alt="emptyImage" class="productItemImage">
                            @endif
                        </a>
                    </div>
                    <div class="itemInfo">
                        <h1>
                            <span>{{ $product->price }}</span>
                            <span>byn</span>
                        </h1>
                        <span>{{ $product->shortTitle }}</span>
                    </div>
                    <div class="productsBtnsContainer">
                        <form action="{{ route('cart.add', $product->id) }}" method="post" class="productsCartBtnForm">
                            @csrf
                            <button class="productsCartBtn">
                                <span>В корзину</span>
                            </button>
                        </form>
                        <form action="{{ route('favorites.add', $product->id) }}" method="post" class="productsFavoriteBtnForm">
                            @csrf
                            <button class="productsFavoriteBtn">
                                <img src="{{ asset('images/favorite.svg') }}" alt="favoriteItemImage" class="favoriteItemImage">
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection