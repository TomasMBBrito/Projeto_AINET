@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 rounded shadow-md">
        <h1 class="text-3xl font-bold text-green-700 mb-6">Frequently Asked Questions</h1>

        <div class="space-y-4" x-data="{ selected: null }">
            @php
                $faqs = [
                    ['title' => 'How do I join the Grocery Club?', 'icon' => 'user-plus', 'content' => 'Simply register an account using the Sign Up button. After creating your account, pay the membership fee to unlock full features.'],
                    ['title' => 'Can I place an order without being a member?', 'icon' => 'shopping-cart', 'content' => 'Guests can browse and add items to the cart, but only members can complete purchases. Join today to unlock full access.'],
                    ['title' => 'How does the virtual card work?', 'icon' => 'credit-card', 'content' => 'Once your membership is active, you receive a virtual card used for secure purchases within the platform. It simplifies payments and helps track expenses.'],
                    ['title' => 'What are the delivery options?', 'icon' => 'truck', 'content' => 'We offer home delivery and community group deliveries to reduce costs. You can select your preference at checkout.'],
                    ['title' => 'Is my personal data safe?', 'icon' => 'lock', 'content' => 'Absolutely. We use encrypted communications and secure authentication to keep your data protected.'],
                    ['title' => 'Still have questions?', 'icon' => 'help-circle', 'content' => 'Feel free to reach out to our support team at <a href="#" class="underline text-green-700">support@groceryclub.com</a>.'],
                ];
            @endphp

            @foreach ($faqs as $index => $faq)
                <div class="border rounded overflow-hidden shadow-sm" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full text-left px-4 py-3 bg-green-100 hover:bg-green-200 flex justify-between items-center">
                        <div class="flex items-center gap-2 text-green-800 font-medium">
                            <i data-lucide="{{ $faq['icon'] }}" class="w-5 h-5"></i>
                            {{ $faq['title'] }}
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transform transition-transform duration-200 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="px-4 pb-4 pt-2 text-gray-700" x-html="@js($faq['content'])"></div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    lucide.createIcons();
</script>
@endsection
