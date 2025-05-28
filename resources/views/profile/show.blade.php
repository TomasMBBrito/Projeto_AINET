@extends('layouts.app')

@section('title', 'O Meu Perfil')

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-8 bg-white shadow rounded-xl">
    <h2 class="text-3xl font-bold mb-6">My profile</h2>

    {{-- Mensagem de sucesso --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

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
                <label class="block text-gray-700 font-medium">Progile photo</label>
                <input type="file" name="photo" class="w-full mt-1 p-2 border border-gray-300 rounded-lg">
            </div>

            @if(auth()->user()->type === 'member' || auth()->user()->type === 'board')
                <div>
                    <label class="block text-gray-700 font-medium">NIF</label>
                    <input type="text" name="nif" value="{{ old('nif', auth()->user()->nif) }}"
                        class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium">Delivery Address</label>
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
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg">
                Save changes
            </button>
        </div>
    </form>

    {{-- Alterar password --}}
    <hr class="my-8">

    <h3 class="text-xl font-bold mb-4">Change Password</h3>
    <form method="POST" action="{{ route('profile.password') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-700 font-medium">Actual Password </label>
            <input type="password" name="current_password" required
                class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">New Password</label>
            <input type="password" name="password" required
                class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Confirm new Password</label>
            <input type="password" name="password_confirmation" required
                class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg">
            Change Password
        </button>
    </form>
</div>
@endsection
