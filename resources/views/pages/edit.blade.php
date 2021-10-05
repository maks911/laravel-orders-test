@extends('layouts.master')

@section('title', 'Изменить заказ')

@section('content')
    <form method="post" class="form" action="{{route('update', $order->id)}}">
        {{csrf_field()}}
        <input name="_method" type="hidden" value="POST">
        <div class="form-group">
            <input type="hidden" value="{{csrf_token()}}" name="_token" />
            <label>E-mail клиента:</label>
            <input type="text" name="client_email" value="{{ $order->client_email }}" class="form-control">
        </div>
        <div class="form-group">
            <label>Партнер:</label>
            {!! Form::select('partner', $partners, old('partner', $order->partner->id), ['class' => 'form-control'] ) !!}
        </div>
        <div class="form-group">
            <label>Products:</label>
            @foreach ($order->products as $product)
                {{ $product->name }} : {{ $product->pivot->quantity }} шт @if(!$loop->last),@endif
            @endforeach
        </div>
        <div class="form-group">
            <label>Статус:</label>
            {!! Form::select('status', $statuses, old('status', $order->status), ['class' => 'form-control'] ) !!}
        </div>
        <div class="form-group">
            <label>Стоимость Заказа:</label>
            {{ $order->total }}
        </div>
        <button type="submit" class="btn btn-primary">Обновить</button>
    </form>
@stop
