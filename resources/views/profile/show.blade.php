@extends('layouts.app')

@section('title', 'O Meu Perfil')

@section('content')
<div class="max-w-4xl mx-auto mt-10 mb-5 p-8 bg-white shadow rounded-xl">
    <h2 class="text-3xl font-bold mb-6">My profile</h2>

    {{-- Mensagem de sucesso --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Erros de validação --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulário de atualização de perfil --}}
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-medium">Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                    class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                    class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Gender</label>
                <select name="gender" class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
                    <option value="M" {{ auth()->user()->gender === 'M' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ auth()->user()->gender === 'F' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Profile photo</label>
                <div class="flex items-center space-x-4 mb-2">
                    @if (auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Foto de Perfil"
                            class="w-24 h-24 object-cover border border-gray-300 rounded-[20px]">
                    @else
                        <div class="w-24 h-24 bg-gray-200 flex items-center justify-center text-gray-500 border border-gray-300 rounded-[20px]">
                            No photo
                        </div>
                    @endif
                </div>
                <input type="file" name="photo" class="w-full mt-1 p-2 border border-gray-300 rounded-lg">
            </div>

            @if(auth()->user()->type === 'member' || auth()->user()->type === 'pending_member' || auth()->user()->type === 'board')
                <div>
                    <label class="block text-gray-700 font-medium">NIF</label>
                    <input type="text" name="nif" value="{{ old('nif', auth()->user()->nif) }}"
                        class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium">Delivery address</label>
                    <input type="text" name="default_delivery_address" value="{{ old('default_delivery_address', auth()->user()->default_delivery_address) }}"
                        class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium">Payment type</label>
                    <select name="default_payment_type" class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
                        <option value="">None</option>
                        <option value="Visa" {{ auth()->user()->default_payment_type === 'Visa' ? 'selected' : '' }}>Visa</option>
                        <option value="PayPal" {{ auth()->user()->default_payment_type === 'PayPal' ? 'selected' : '' }}>PayPal</option>
                        <option value="MB WAY" {{ auth()->user()->default_payment_type === 'MB WAY' ? 'selected' : '' }}>MB WAY</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium">Payment reference</label>
                    <input type="text" name="default_payment_reference" value="{{ old('default_payment_reference', auth()->user()->default_payment_reference) }}"
                        class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
                </div>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg">
                Save changes
            </button>
        </div>
    </form>

    {{-- Formulário separado para remover foto --}}
    @if (auth()->user()->photo)
        <form action="{{ route('profile.removePhoto') }}" method="POST" class="mt-4">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg">
                Remove profile photo
            </button>
        </form>
    @endif

    {{-- Alterar password --}}
    <hr class="my-8">

    <h3 class="text-xl font-bold mb-4">Change password</h3>
    <form method="POST" action="{{ route('profile.password') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-700 font-medium">Actual password</label>
            <input type="password" name="current_password" required
                class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">New password</label>
            <input type="password" name="password" required
                class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Confirm new password</label>
            <input type="password" name="password_confirmation" required
                class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
        </div>

        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-6 rounded-lg">
            Change password
        </button>
    </form>
</div>
@endsection
