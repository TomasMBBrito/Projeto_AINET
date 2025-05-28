<header class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logotipo -->
        <a href="{{ route('home') }}" class="text-2xl font-bold text-green-700">Grocery Club</a>

        <!-- Navegação -->
        <nav class="space-x-4">
            <a href="{{ route('catalog.index') }}" class="text-gray-700 hover:text-green-600">Catalog</a>
            @auth
                <a href="{{ route('orders-stock.index') }}" class="text-gray-700 hover:text-green-600">My purchases</a>
                <a href="{{ route('profile.show') }}" class="text-gray-700 hover:text-green-600">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-red-600">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600">Login</a>
                <a href="{{ route('register') }}" class="text-gray-700 hover:text-green-600">Sign up</a>
            @endauth
        </nav>
    </div>
</header>
