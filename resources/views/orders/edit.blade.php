@extends('layouts.app')

@section('content')
<div class="row">
    <h1>Изменение заказа №{{ $order->id }}</h1>

    @if (session('update_success'))
        <div class="alert alert-success">
            {{ session('update_success') }}
        </div>
    @endif
</div>

<form action="{{ route('orders/update', $order->id) }}" method="POST">
    <!-- Laravel 6.*
        @csrf
        @method('PUT')
    -->
    <!-- Laravel 5.5.* -->
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <!--
    поля для редактирования:
    email_клиента(редактирование, обязательное)
    партнер(редактирование, обязательное)
    продукты(вывод наименования + количества единиц продукта)
    статус заказа(редактирование, обязательное)
    стоимость заказ(вывод)
    сохранение изменений в заказе
    -->
    <div class="row p-3">
        @if ($errors->has('client_email'))
        <p class="alert alert-warning" role="alert">{{ __($errors->first('client_email')) }}</p>
        @endif

        <label for="client_email">Email клиента *</label>
        <input id="client_email"
               type="text"
               name="client_email"
               value="{{ $order->client_email }}" />
    </div>
    <div class="row p-3">
        @if ($errors->has('partner_id'))
        <p class="alert alert-warning" role="alert">{{ __($errors->first('partner_id')) }}</p>
        @endif

        <label for="partner_id">Партнер *</label>

        <select id="partner_id"
                name="partner_id">

            <option value="">-</option>
            @foreach ($partners as $partner)
            <option value="{{ $partner->id }}" @if($partner->id === $order->partner->id) selected @endif>{{ $partner->name }}</option>
            @endforeach

        </select>
    </div>
    <div class="row">
        <div class="col-lg-2">
            <label for="products">Продукты</label>
        </div>
        <div class="col-lg-4">
            @foreach ($order->products as $product)
            <div class="row">
                <label for="product_{{ $product->id }}">{{ $product->name }}</label>, в количестве:

                <input id="product_{{ $product->id }}"
                       type="text"
                       name="products[{{ $product->id }}]"
                       size="6"
                       value="{{ $product->pivot->quantity }}" />
            </div>
            @endforeach
        </div>
    </div>
    <div class="row p-3">
        @if ($errors->has('status'))
        <p class="alert alert-warning" role="alert">{{ __($errors->first('status')) }}</p>
        @endif

        <label for="status">Статус *</label>

        <select id="status"
                name="status">

            <option value="">-</option>
            @foreach ($order->getOrderStatusList() as $k => $v)
            <option value="{{ $k }}" @if($k === $order->status) selected @endif>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div class="row p-3">
        Сумма заказа: <span id="order_total_amount" class="badge">{{ $order->getTotalAmount() }}</span>
    </div>
    <div class="row p-3">
        <button class="button btn-primary">Сохранить заказ</button>
    </div>
</form>
@endsection