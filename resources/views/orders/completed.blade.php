@component('mail::message')
# Order #{{ $order->id }} Completed

Dear {{ $order->member->name }},

Your order placed on {{ $order->date->format('d/m/Y') }} has been marked as **completed**.

Thank you for shopping with us!

@component('mail::button', ['url' => route('orders.show', $order->id)])
View Order
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
