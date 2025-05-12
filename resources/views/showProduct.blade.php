@extends('layouts.asset')
@section('content')
    <div class="content max-w-6xl mx-auto p-6">
        <a href="{{ route('products.index') }}" class="text-red-600 hover:underline mb-6 inline-block">Назад</a>
        @if (Auth::check() && Auth::user()->name === 'Admin')
            <div class="deleteEditBtns flex gap-4 mb-6">
                <div class="edit">
                    <button class="editButton bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-all">
                        <a href="{{ route('product.edit', $product->id) }}" class="flex items-center gap-2 text-white no-underline">
                            <img src="{{ asset('images/pencil.svg') }}" alt="editButtonImg" class="editButtonImg w-6 h-6">
                            <span>Изменить</span>
                        </a>
                    </button>
                </div> 
                <div class="delete">
                    <form action="{{ route('product.delete', $product->id) }}" method="post">
                        @csrf
                        @method('delete')
                        <button class="deleteButton bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-all" type="submit">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('images/delete.svg') }}" alt="deleteButtonImg" class="deleteButtonImg w-6 h-6">
                                <span>Удалить</span>
                            </span>
                        </button>
                    </form>
                </div> 
            </div>
        @endif
        <div class="productCard flex flex-col md:flex-row gap-6">
            <div class="photos w-full md:w-3/5">
                <div class="photosColumn flex flex-col items-center">
                    <div class="product-images">
                        @if($images)
                            <img src="{{ Storage::url($images->previewImagePath) }}" alt="Image 1" class="smallImage w-20 h-20 object-cover rounded-md mb-4 hover:scale-110 transition-all">
                            <img src="{{ Storage::url($images->imagePath1) }}" alt="Image 2" class="smallImage w-20 h-20 object-cover rounded-md mb-4 hover:scale-110 transition-all">
                            <img src="{{ Storage::url($images->previewImagePath) }}" alt="Image 1 Repeated" class="smallImage w-20 h-20 object-cover rounded-md mb-4 hover:scale-110 transition-all">
                            <img src="{{ Storage::url($images->imagePath1) }}" alt="Image 2 Repeated" class="smallImage w-20 h-20 object-cover rounded-md mb-4 hover:scale-110 transition-all">
                        @else
                            <p class="text-gray-600">Изображения отсутствуют.</ volts>
                        @endif
                    </div>
                </div>
                <div class="bigPhoto flex-1">
                    <img src="{{ Storage::url($images->previewImagePath) }}" alt="previewImage" class="previewImage w-full h-auto rounded-md">
                </div>
            </div>
            <div class="infos w-full md:w-2/5">
                <div class="productInfo flex flex-col gap-4">
                    <h1 class="title text-3xl font-montserrat-bold text-gray-800">{{ $product->title }}</h1>
                    <h3 class="shortTitle text-lg text-gray-600">{{ $product->shortTitle }}</h3>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Артикул:</label>
                        <span class="article text-gray-600">{{ $product->article }}</span>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Бренд:</label>
                        <span class="brandId text-gray-600">{{ optional($product->brand)->name ?? 'Не указано' }}</span>
                    </div>
                    <div>
 Gilles
                        <label class="text-sm font-semibold text-gray-700">Коллекция:</label>
                        <span class="collectionId text-gray-600">{{ optional($product->collection)->name ?? 'Не указано' }}</span>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Категория:</label>
                        <span class="categoryId text-gray-600">{{ optional($product->category)->name ?? 'Не указано' }}</span>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Тип:</label>
                        <span class="typeId text-gray-600">{{ optional($product->type)->name ?? 'Не указано' }}</span>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Цвета:</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->color ?? [] as $color)
                                @php
                                    $colorMap = [
                                        'Red' => '#FF0000',
                                        'Orange' => '#FFA500',
                                        'Yellow' => '#FFFF00',
                                        'Green' => '#008000',
                                        'Blue' => '#0000FF',
                                        'Purple' => '#800080',
                                        'Brown' => '#A52A2A',
                                        'Black' => '#000000'
                                    ];
                                    $colorCode = $colorMap[$color] ?? '#000000';
                                @endphp
                                <span class="color-circle w-6 h-6 rounded-full border-2 border-gray-300" style="background-color: {{ $colorCode }};"></span>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Пол:</label>
                        <span class="sex text-gray-600">{{ $product->sex }}</span>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Размеры:</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->size ?? [] as $size)
                                <span class="size-button w-8 h-8 flex items-center justify-center bg-gray-200 text-gray-800 rounded-md border-2 border-gray-300">{{ $size }}</span>
                            @endforeach
                        </div>
                    </div>
                    <h1 class="price text-3xl font-montserrat-bold text-green-600">{{ $product->price }} <span class="priceSpan">BYN</span></h1>
                    <div class="btnsContainer flex flex-col md:flex-row gap-4">
                        <form action="{{ route('cart.add', $product->id) }}" method="post" class="productsCartBtnForm w-full md:w-1/2">
                            @csrf
                            <button class="cartBtn w-full h-12 bg-green-600 text-white rounded-lg hover:bg-white hover:text-green-600 hover:border-2 hover:border-green-600 transition-all">В корзину</button>
                        </form>
                    </div>
                    <div class="descriptionBlock flex flex-col gap-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Описание:</label>
                            <span class="description text-gray-600">{{ $product->description }}</span>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Состав:</label>
                            <span class="composition text-gray-600">{{ $product->composition }}</span>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Страна дизайна:</label>
                            <span class="designCountry text-gray-600">{{ $product->designCountry }}</span>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Страна производства:</label>
                            <span class="manufacturenCountry text-gray-600">{{ $product->manufacturenCountry }}</span>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Импортер:</label>
                            <span class="importer text-gray-600">{{ $product->importer }}</span>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Наличие:</label>
                            <span class="availability text-gray-600">{{ $product->availability ? 'В наличии' : 'Нет в наличии' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection