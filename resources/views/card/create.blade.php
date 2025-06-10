@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Criar Cartão Virtual</h2>
    <p>Para prosseguir com a compra, é necessário criar um cartão virtual.</p>

    <form action="{{ route('card.create') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Criar Cartão</button>
    </form>
</div>
@endsection
