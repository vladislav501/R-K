@extends('layouts.app')

@section('title', 'Проверка поставки')

@section('content')
    <div class="supply-check">
        <div class="supply-check-sidebar">
            <h2>Менеджер Панель</h2>
            <ul>
                <li><a href="{{ route('manager.supplies.index') }}">Поставки</a></li>
                <li><a href="{{ route('manager.supplies.archive') }}">Архив поставок</a></li>
            </ul>
        </div>
        <div class="supply-check-content">
            <h1>Проверка поставки №{{ $supply->id }}</h1>
            <p><strong>Пункт выдачи:</strong> {{ $supply->store->name }}</p>
            <p><strong>Статус:</strong> {{ $supply->status }}</p>
            <form action="{{ route('manager.supplies.confirm', $supply) }}" method="POST">
                @csrf
                <div class="supply-check-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Товар</th>
                                <th>Цвет</th>
                                <th>Размер</th>
                                <th>Отправлено</th>
                                <th>Получено</th>
                                <th>Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supply->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->color ? $item->color->name : '-' }}</td>
                                    <td>{{ $item->size ? $item->size->name : '-' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>
                                        <input type="number" name="items[{{ $item->id }}][received_quantity]" min="0" max="{{ $item->quantity }}" class="received-quantity" disabled>
                                        <input type="hidden" name="items[{{ $item->id }}][id]" value="{{ $item->id }}">
                                    </td>
                                    <td>
                                        <label>
                                            <input type="radio" name="items[{{ $item->id }}][is_fully_received]" value="1" class="fully-received" required> Полностью
                                        </label>
                                        <label>
                                            <input type="radio" name="items[{{ $item->id }}][is_fully_received]" value="0" class="partially-received" required> Частично
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @error('items')
                    <div class="supply-check-error">{{ $message }}</div>
                @enderror
                <button type="submit" class="supply-check-confirm">Подтвердить</button>
            </form>
            <a href="{{ route('manager.supplies') }}" class="supply-check-back">Вернуться к поставкам</a>
            <button class="supply-check-back-to-top">
                <i class="fi fi-rr-arrow-small-up"></i>
            </button>
        </div>
    </div>
    <script>
        document.querySelector('.supply-check-back-to-top').addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        document.querySelectorAll('.fully-received, .partially-received').forEach(radio => {
            radio.addEventListener('change', function() {
                const row = this.closest('tr');
                const receivedInput = row.querySelector('.received-quantity');
                if (this.classList.contains('partially-received')) {
                    receivedInput.disabled = false;
                    receivedInput.required = true;
                } else {
                    receivedInput.disabled = true;
                    receivedInput.required = false;
                    receivedInput.value = '';
                }
            });
        });
    </script>
@endsection