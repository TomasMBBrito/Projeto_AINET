@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold text-green-700 mb-6 flex items-center gap-2">
            <i data-lucide="phone" class="w-6 h-6"></i> Contact Us
        </h1>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Contact Info -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">We're here to help</h2>
                <p class="text-gray-600 mb-4">Feel free to reach out to us with any questions, suggestions, or feedback you may have.</p>
                <ul class="space-y-3 text-sm text-gray-700">
                    <li class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-4 h-4 text-green-600"></i>
                        <a href="#" class="hover:underline">support@groceryclub.com</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-4 h-4 text-green-600"></i>
                        <span>+351 912 345 678</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-green-600"></i>
                        <span>Rua dos Produtos Frescos, nº 42, Lisboa</span>
                    </li>
                </ul>
            </div>

            <!-- Contact Form -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Send us a message</h2>
                <form action="#" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Your Name</label>
                        <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-600 focus:border-green-600" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-600 focus:border-green-600" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea name="message" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-600 focus:border-green-600" required></textarea>
                    </div>
                    <div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-700 text-white rounded hover:bg-green-800 transition">
                            <i data-lucide="send" class="w-4 h-4 mr-2"></i> Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
@endsection
