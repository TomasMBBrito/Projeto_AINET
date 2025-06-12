@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Pagamento da Quota</h2>

    @if (session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('membership.process') }}">
        @csrf

        <p class="text-lg mb-4">Valor da Quota: <strong>{{ number_format($membershipFee, 2) }} €</strong></p>

        <button type="submit" 
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
            Pagar Quota
        </button>
    </form>
</div>
@endsection
