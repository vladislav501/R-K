@extends('layouts.app')

@section('title', 'Проверка поставки')

@section('content')
    <h1>Проверка поставки №{{ $supply->id }}</h1>
    <p>Пункт выдачи: {{ $supply->store->name }}</p>
    <p>Статус: {{ $supply->status }}</p>
    <form action="{{ route('manager.supplies.confirm', $supply) }}" method="POST">
        @csrf
        <table class="table-auto w-full">
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
        @error('items')
            <span class="error">{{ $message }}</span>
        @enderror
        <button type="submit" class="btn btn-primary">Подтвердить</button>
    </form>

    <a href="/manager/supplies" class="btn btn-secondary">Вернуться к поставкам</a>
    
    <script>
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