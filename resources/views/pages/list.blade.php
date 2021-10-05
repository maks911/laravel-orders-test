@extends('layouts.master')

@section('title', 'Список заказов')

@section('content')
    @if(\Session::has('success'))
        <div class="alert alert-success">
            {{\Session::get('success')}}

            <a href="/orders/{{ \Session::get('id') }}">
                Посмотреть
            </a>
        </div>
    @endif
    <h1>
        Список Заказов
    </h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Партнер</th>
            <th scope="col">Стоимость заказа</th>
            <th scope="col">Состав Заказа</th>
            <th scope="col">Статус</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>
                    <a href="{{ url("/orders/$order->id") }}">
                        {{ $order->id }}
                    </a>
                </td>
                <td>{{ $order->partner->name }}</td>
                <td>{{ $order->total }}</td>
                <td>
                    @foreach ($order->products as $product)
                        {{ $product->name }} @if(!$loop->last),@endif
                    @endforeach
                </td>
                <td>{{ $order->status }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <ul class="pagination">
        @for ($i = 1; $i <= $pagesCount; $i++)
            <li class="pagination__item">
                <a class="pagination__link" href="{{ url("/orders?page=$i") }}">
                    {{ $i }}
                </a>
            </li>
        @endfor
    </ul>
@stop
