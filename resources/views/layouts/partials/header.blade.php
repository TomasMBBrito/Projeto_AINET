<header class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logotipo -->
        <a href="{{ route('home') }}" class="text-2xl font-bold text-green-700">Grocery Club</a>

        <!-- Navegação -->
        <nav class="space-x-5">
            {{-- Navigation Bar with Dropdown --}}
            {{-- Navigation Bar with Dropdown --}}
<div class="relative inline-block group">
    <a href="#" class="text-gray-700 hover:text-green-600 px-2 py-1 inline-block">
        Painel de Administração
    </a>

    {{-- Dropdown menu visível ao passar o rato no div pai (incluindo o menu) --}}
    <ul class="absolute hidden group-hover:block bg-green-800 rounded shadow-lg min-w-[150px] z-50 mt-2 right-0">
        <li>
            <a href="{{ route('categories.index') }}" class="block px-4 py-2 text-white hover:bg-green-700">
                Categories
            </a>
        </li>
        <li>
            <a href="{{ route('products.index') }}" class="block px-4 py-2 text-white hover:bg-green-700">
                Products
            </a>
        </li>
        <li>
            <a href="{{ route('settings.edit') }}" class="block px-4 py-2 text-white hover:bg-green-700">
                Membership Fee
            </a>
        </li>
        <li>
            <a href="{{ route('shipping-costs.index') }}" class="block px-4 py-2 text-white hover:bg-green-700">
                Shipping Costs
            </a>
        </li>
    </ul>
</div>
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
