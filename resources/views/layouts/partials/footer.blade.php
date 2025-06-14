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
            <p>Email: info@groceryclub.com</p>
            <p>phone number: +351 123 456 789</p>
            <div class="space-x-4 mt-2">
                <a href="#" class="hover:text-gray-300">Facebook</a>
                <a href="#" class="hover:text-gray-300">Instagram</a>
                <a href="#" class="hover:text-gray-300">Twitter</a>
            </div>
        </div>
    </div>
    <div class="text-center mt-8 text-sm">
        &copy; {{ date('Y') }} Grocery Club has all rights reserved.
    </div>
</footer>
