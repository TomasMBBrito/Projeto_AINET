@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Configurações Gerais</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Membership Fee (€)</label>
            <input type="number" step="0.01" name="membership_fee" class="form-control"
                   value="{{ old('membership_fee', $setting->membership_fee) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>
@endsection
