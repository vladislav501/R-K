@extends('layouts.app')

       @section('content')
           <div class="container mx-auto p-4">
               <h1 class="text-2xl font-bold mb-4">Товары</h1>
               <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-4">Добавить товар</a>
               @if(session('success'))
                   <div class="alert alert-success">{{ session('success') }}</div>
               @endif
               <table class="table-auto w-full">
                   <thead>
                       <tr>
                           <th>Название</th>
                           <th>Цена</th>
                           <th>Бренд</th>
                           <th>Категория</th>
                           <th>Действия</th>
                       </tr>
                   </thead>
                   <tbody>
                       @foreach($products as $product)
                           <tr>
                               <td>{{ $product->name }}</td>
                               <td>{{ $product->price }}</td>
                               <td>{{ $product->brand->name }}</td>
                               <td>{{ $product->category->name }}</td>
                               <td>
                                   <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary">Редактировать</a>
                                   <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                       @csrf
                                       @method('DELETE')
                                       <button type="submit" class="btn btn-danger">Удалить</button>
                                   </form>
                               </td>
                           </tr>
                       @endforeach
                   </tbody>
               </table>
           </div>
       @endsection
       ?>