@extends('layouts.app')

@section('title', 'Add Employee')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-6">Add Employee</h2>

    @if (session('error'))
            <div class="mt-4 p-4 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
        @csrf

        <label class="block font-medium">Name</label>
        <input type="text" name="name" required class="w-full mb-4 p-2 border rounded">

        <label class="block font-medium">Email</label>
        <input type="email" name="email" required class="w-full mb-4 p-2 border rounded">

        <label class="block font-medium">Password</label>
        <input type="password" name="password" required class="w-full mb-4 p-2 border rounded">

        <label class="block font-medium">Gender</label>
        <select name="gender" class="w-full mb-4 p-2 border rounded">
            <option value="M">Male</option>
            <option value="F">Female</option>
        </select>

        <label class="block font-medium">Profile photo</label>
        <input type="file" name="photo" class="w-full mb-4 p-2 border rounded">

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save</button>
    </form>
</div>
@endsection
