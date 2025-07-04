<!-- Lucide CDN para ícones -->
<script src="https://unpkg.com/lucide@latest"></script>

<header class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between flex-wrap">
        <!-- Logotipo -->
        <a href="{{ route('home') }}" class="text-2xl font-bold text-green-700">
            Grocery Club
        </a>

        <button id="menu-toggle" class="lg:hidden text-green-700 focus:outline-none">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>

        <!-- Navegação -->
        <nav id="menu" class="w-full lg:w-auto mt-4 lg:mt-0 hidden lg:flex flex-col lg:flex-row lg:items-center space-y-2 lg:space-y-0 lg:space-x-3 text-sm">
            <a href="{{ route('favorites.index') }}"
                    class="flex items-center gap-1 text-black hover:text-white hover:bg-green-600 px-3 py-2 rounded transition">
                    <i data-lucide="heart" class="w-4 h-4"></i> Favorites
            </a>

            <a href="{{ route('catalog.index') }}"
                class="flex items-center gap-1 text-black hover:text-white hover:bg-green-600 px-3 py-2 rounded transition">
                <i data-lucide="list" class="w-4 h-4"></i> Catalog
            </a>
            
            @auth
                @if (Auth::user()->type === 'board')
                    <div class="relative group">
                        <!-- Botão principal -->
                        <a href="#"
                            class="flex items-center gap-1 text-black hover:text-white hover:bg-green-600 px-3 py-2 rounded transition">
                            <i data-lucide="settings" class="w-4 h-4"></i> Admin Panel
                        </a>

                        <!-- Submenu -->
                        <ul
                            class="absolute hidden group-hover:block bg-green-800 rounded shadow-lg min-w-[150px] z-50 right-0">
                            <li>
                                <a href="{{ route('users.index') }}"
                                    class="block px-4 py-2 text-white hover:bg-green-700 transition">Users</a>
                            </li>
                            <li>
                                <a href="{{ route('categories.index') }}"
                                    class="block px-4 py-2 text-white hover:bg-green-700 transition">Categories</a>
                            </li>
                            <li>
                                <a href="{{ route('products.index') }}"
                                    class="block px-4 py-2 text-white hover:bg-green-700 transition">Products</a>
                            </li>
                            <li>
                                <a href="{{ route('orders.index') }}"
                                    class="block px-4 py-2 text-white hover:bg-green-700 transition">Orders</a>
                            </li>
                            <li>
                                <a href="{{ route('settings.edit') }}"
                                    class="block px-4 py-2 text-white hover:bg-green-700 transition">Membership Fee</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings.shipping_costs.index') }}"
                                    class="block px-4 py-2 text-white hover:bg-green-700 transition">Shipping cost</a>
                            </li>
                        </ul>
                    </div>
                @endif
                @if (Auth::user()->type === 'employee')
                    <a href="{{ route('orders.index') }}"
                        class="flex items-center gap-1 text-black hover:text-white hover:bg-green-600 px-3 py-2 rounded transition">
                        <i data-lucide="clipboard-list" class="w-4 h-4"></i> Orders
                    </a>
                @endif

                @if (Auth::user()->type !== 'employee')
                    <a href="{{ route('cart.index') }}"
                        class="flex items-center gap-1 text-black hover:text-white hover:bg-green-600 px-3 py-2 rounded transition">
                        <i data-lucide="shopping-cart" class="w-4 h-4"></i> Cart
                    </a>

                    <a href="{{ route('purchase.index') }}"
                        class="flex items-center gap-1 text-black hover:text-white hover:bg-green-600 px-3 py-2 rounded transition">
                        <i data-lucide="shopping-cart" class="w-4 h-4"></i> My Purchases
                    </a>

                    <a href="{{ route('card.index') }}"
                        class="flex items-center gap-1 text-black hover:text-white hover:bg-green-600 px-3 py-2 rounded transition">
                        <i data-lucide="credit-card" class="w-4 h-4"></i> My Card
                    </a>
                @endif

                @if (Auth::user()->type === 'pending_member')
                    <a href="{{ route('membership.pay') }}"
                        class="flex items-center gap-1 text-black hover:text-white hover:bg-green-600 px-3 py-2 rounded transition">
                        <i data-lucide="banknote" class="w-4 h-4"></i> Pay Membership
                    </a>
                @endif

                <a href="{{ route('statistics') }}"
                class="flex items-center gap-1 text-black hover:text-white hover:bg-green-600 px-3 py-2 rounded transition">
                    <i data-lucide="chart-candlestick" class="w-4 h-4"></i> Statistics
                </a>

                <a href="{{ route('profile.show') }}"
                    class="flex items-center gap-1 text-black hover:text-white hover:bg-green-600 px-3 py-2 rounded transition">
                    <i data-lucide="user" class="w-4 h-4"></i> Profile
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-1 text-black hover:text-white hover:bg-red-600 px-3 py-2 rounded transition">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                    </button>
                </form>
            @else
                <a href="{{ route('cart.index') }}"
                    class="flex items-center gap-1 text-black hover:text-white hover:bg-green-600 px-3 py-2 rounded transition">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i> Cart
                </a>
                <a href="{{ route('login') }}"
                    class="flex items-center gap-1 text-black hover:text-white hover:bg-green-600 px-3 py-2 rounded transition">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Login
                </a>

                <a href="{{ route('register') }}"
                    class="flex items-center gap-1 text-black hover:text-white hover:bg-green-600 px-3 py-2 rounded transition">
                    <i data-lucide="user-plus" class="w-4 h-4"></i> Sign Up
                </a>
            @endauth
        </nav>
    </div>
</header>

<script>
    lucide.createIcons();

    document.getElementById('menu-toggle').addEventListener('click', () => {
        const menu = document.getElementById('menu');
        menu.classList.toggle('hidden');
    });
</script>
