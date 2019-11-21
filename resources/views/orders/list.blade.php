@extends('layouts.app')

@section('content')
<div class="row">
    <h3>Список заказов</h3>

    <table class="table-bordered m-3 p-3">
        <th class="p-3 m-3">ид_заказа</th>
        <th class="p-3 m-3">название_партнера</th>
        <th class="p-3 m-3">стоимость_заказа</th>
        <th class="p-3 m-3">наименование_состав_заказа</th>
        <th class="p-3 m-3">статус_заказа</th>

        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td class="p-3 m-3"><a href="{{ route('orders/edit', $order->id) }}" class="btn btn-primary">{{ $order->id }}</a></td>
                <td class="p-3 m-3">{{ $order->partner->name }}</td>
                <td class="p-3 m-3">{{ $order->getTotalAmount() }}</td>
                <td class="p-3 m-3">{{ $order->products->implode('name', ', ') }}</td>
                <td class="p-3 m-3">{{ $order->getStatusName() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection