@extends('layouts.asset')
@section('content')
    <div class="content">
        <h1 class="contentTitle">Добавить новую коллекцию</h1>
        <div class="leftMenu">
            <button>
                <a href="{{ route('addProduct.index') }}">
                    <span>Товар</span>
                </a>
            </button>
            <button>
                <a href="{{ route('addBrand.index') }}">
                    <span>Бренд одежды</span>
                </a>
            </button>
            <button>
                <a href="{{ route('addCollection.index') }}">
                    <span>Коллекция одежды</span>
                </a>
            </button>
        </div>
        <form action="{{ route('collection.store') }}" method="post" class="addProductForm">
            @csrf
            
        </form>
        <button><a href="{{ route('admin.index') }}">Назад</a></button>
    </div>
@endsection