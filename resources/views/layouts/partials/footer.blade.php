<footer class="bg-green-700 text-white py-8">
    <div class="container mx-auto px-4 text-center grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Sobre -->
        <div>
            <h5 class="font-bold mb-2">About</h5>
            <p>Grocery Club is your trusted online store for fresh, quality products.</p>
        </div>

        <!-- Links Úteis -->
        <div>
            <h5 class="font-bold mb-2">Useful Links</h5>
            <ul>
                <li><a href="{{ route('catalog.index') }}" class="hover:underline">Catalog</a></li>
                <li><a href="{{ route('about') }}" class="hover:underline">About us</a></li>
                <li><a href="{{ route('contact') }}" class="hover:underline">Contacts</a></li>
                <li><a href="{{ route('faq') }}" class="hover:underline">FAQ</a></li>
            </ul>
        </div>

        <!-- Contactos -->
        <div>
            <h5 class="font-bold mb-2">Contacts</h5>
            <p>Email: support@groceryclub.com</p>
            <p>Phone number: +351 912 345 678</p>
            <div class="flex flex-col items-center space-y-2 mt-4">
                <a href="#" class="flex items-center gap-1 hover:text-gray-300">
                    <i data-lucide="facebook" class="w-4 h-4"></i> Facebook
                </a>
                <a href="#" class="flex items-center gap-1 hover:text-gray-300">
                    <i data-lucide="instagram" class="w-4 h-4"></i> Instagram
                </a>
                <a href="#" class="flex items-center gap-1 hover:text-gray-300">
                    <i data-lucide="twitter" class="w-4 h-4"></i> Twitter
                </a>
            </div>
        </div>
    </div>
    <div class="text-center mt-8 text-sm">
        &copy; {{ date('Y') }} Grocery Club has all rights reserved.
    </div>
</footer>

<script>
    lucide.createIcons();
</script>
