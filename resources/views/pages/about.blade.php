@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 rounded shadow-md">
        <h1 class="text-3xl font-bold text-green-700 mb-4">About Grocery Club</h1>

        <p class="text-gray-700 text-base mb-6">
            Welcome to <strong>Grocery Club</strong> — your community-powered online grocery marketplace!
            Our mission is to make grocery shopping more accessible, affordable, and sustainable by connecting members directly with local producers and trusted suppliers.
        </p>

        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div>
                <h2 class="text-xl font-semibold text-green-700 mb-2 flex items-center gap-2">
                    <i data-lucide="users" class="w-5 h-5"></i> A Club for Everyone
                </h2>
                <p class="text-gray-600">
                    Members of the club enjoy exclusive access to fresh products, community discounts, and shared delivery options.
                    Whether you’re a regular shopper, employee, or part of our board, your experience is tailored for you.
                </p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-green-700 mb-2 flex items-center gap-2">
                    <i data-lucide="leaf" class="w-5 h-5"></i> Sustainable Shopping
                </h2>
                <p class="text-gray-600">
                    We care about the planet. By centralizing deliveries and prioritizing local goods, Grocery Club helps reduce packaging waste and carbon emissions.
                </p>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-green-100 p-4 rounded text-center">
                <i data-lucide="shopping-cart" class="w-8 h-8 text-green-700 mx-auto mb-2"></i>
                <h3 class="font-semibold">Smart Cart System</h3>
                <p class="text-sm text-gray-600">Track, manage and optimize your shopping with ease.</p>
            </div>
            <div class="bg-green-100 p-4 rounded text-center">
                <i data-lucide="credit-card" class="w-8 h-8 text-green-700 mx-auto mb-2"></i>
                <h3 class="font-semibold">Secure Payments</h3>
                <p class="text-sm text-gray-600">Pay safely using your virtual Grocery Club card.</p>
            </div>
            <div class="bg-green-100 p-4 rounded text-center">
                <i data-lucide="truck" class="w-8 h-8 text-green-700 mx-auto mb-2"></i>
                <h3 class="font-semibold">Flexible Delivery</h3>
                <p class="text-sm text-gray-600">Choose pickup or group delivery — whatever suits you best.</p>
            </div>
        </div>

        <div class="bg-green-50 p-6 rounded shadow-sm">
            <h2 class="text-xl font-semibold text-green-800 mb-3 flex items-center gap-2">
                <i data-lucide="mail" class="w-5 h-5"></i> Get in Touch
            </h2>
            <p class="text-gray-700 mb-2">Questions? Feedback? We'd love to hear from you!</p>
            <p class="text-gray-600">Reach us at: <a href="mailto:support@groceryclub.com" class="text-green-700 underline">support@groceryclub.com</a></p>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
