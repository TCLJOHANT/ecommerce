<x-mail::message>
# Orden realizada satisfactoriamente!

Gracias por su compra. el numero de orden es {{$order->id}}

Para ver el detalle de su orden, haga click en el siguiente enlace:

<x-mail::button :url="$url">
Ver Orden
</x-mail::button>

Gracias.<br>
{{ config('app.name') }}
</x-mail::message>
