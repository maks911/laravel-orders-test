Заказ № {{ $order->id }} завершен.<br>
<br><br>Состав заказа:<br>
@foreach ($order->products as $product)
    {{ $product->name }} @if(!$loop->last),@endif
@endforeach
<br><br>Стоимость: {{ $order->total }}


