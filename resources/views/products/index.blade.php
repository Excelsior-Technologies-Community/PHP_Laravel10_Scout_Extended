<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Studio Pro</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-black min-h-screen text-white">

    <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">

        <div>
            <h1 class="text-3xl font-bold">
                ⚡ Product Studio Pro
            </h1>
            <p class="text-gray-400 text-sm">
                Smart Search • Fast Management • Modern UI
            </p>
        </div>

        <a href="/product/create"
            class="bg-indigo-500 hover:bg-indigo-600 px-5 py-3 rounded-xl transition">

            + New Product

        </a>

    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">

        @if(session('success'))
        <div class="mb-6 bg-green-500/20 border border-green-500 text-green-300 px-5 py-3 rounded-xl">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-5 rounded-2xl mb-8 relative">

            <form method="GET" class="flex gap-3" id="search-form">

                <div class="relative w-full">

                    <input
                        type="text"
                        name="search"
                        id="search-input"
                        value="{{ request('search') }}"
                        placeholder="Search products..."
                        autocomplete="off"
                        onfocus="showSuggestions()"
                        class="w-full px-5 py-3 rounded-xl bg-black/30 border border-gray-600 text-white focus:ring-2 focus:ring-indigo-500 outline-none">

                    @if(count($searchHistory) > 0 || count($trending) > 0)
                    <div id="search-suggestions" class="hidden absolute left-0 right-0 top-full mt-2 bg-gray-900 border border-white/20 rounded-xl shadow-xl z-20 overflow-hidden">

                        @if(count($searchHistory) > 0)
                        <div class="px-4 py-2 text-xs text-gray-400 border-b border-white/10 flex justify-between items-center">
                            <span>Recent Searches</span>
                            <form action="/search-history/clear" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300">Clear</button>
                            </form>
                        </div>

                        @foreach($searchHistory as $term)
                        <div onclick="selectSuggestion('{{ $term }}')" class="px-4 py-2 text-sm text-gray-200 hover:bg-white/10 cursor-pointer">
                            🕒 {{ $term }}
                        </div>
                        @endforeach
                        @endif

                        @if(count($trending) > 0)
                        <div class="px-4 py-2 text-xs text-gray-400 border-t border-white/10">
                            Trending Now
                        </div>

                        @foreach($trending as $term)
                        <div onclick="selectSuggestion('{{ $term }}')" class="px-4 py-2 text-sm text-gray-200 hover:bg-white/10 cursor-pointer">
                            🔥 {{ $term }}
                        </div>
                        @endforeach
                        @endif

                    </div>
                    @endif

                </div>

                <button class="bg-indigo-500 px-6 rounded-xl hover:bg-indigo-600 transition">
                    Search
                </button>

                @if(request('search') || request('category') || request('min_price') || request('max_price') || request('sort'))
                <a href="/" class="bg-gray-700 px-5 py-3 rounded-xl hover:bg-gray-600">
                    Reset
                </a>
                @endif

            </form>

        </div>

        <div class="grid grid-cols-12 gap-6">

            <div class="col-span-12 md:col-span-3">

                <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-5 rounded-2xl space-y-6">

                    <form method="GET" id="filter-form">

                        <input type="hidden" name="search" value="{{ request('search') }}">

                        <div>
                            <h3 class="text-sm font-semibold text-gray-300 mb-3">Category</h3>

                            <select name="category" onchange="document.getElementById('filter-form').submit()"
                                class="w-full px-4 py-2 rounded-xl bg-black/30 border border-gray-600 text-white text-sm">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-5">
                            <h3 class="text-sm font-semibold text-gray-300 mb-3">Price Range</h3>

                            <div class="flex gap-2">
                                <input
                                    type="number"
                                    name="min_price"
                                    value="{{ request('min_price') }}"
                                    placeholder="Min"
                                    class="w-1/2 px-3 py-2 rounded-xl bg-black/30 border border-gray-600 text-white text-sm">

                                <input
                                    type="number"
                                    name="max_price"
                                    value="{{ request('max_price') }}"
                                    placeholder="Max"
                                    class="w-1/2 px-3 py-2 rounded-xl bg-black/30 border border-gray-600 text-white text-sm">
                            </div>

                            <button class="mt-3 w-full bg-indigo-500 hover:bg-indigo-600 px-4 py-2 rounded-xl text-sm transition">
                                Apply
                            </button>
                        </div>

                        <div class="mt-5">
                            <h3 class="text-sm font-semibold text-gray-300 mb-3">Sort By</h3>

                            <select name="sort" onchange="document.getElementById('filter-form').submit()"
                                class="w-full px-4 py-2 rounded-xl bg-black/30 border border-gray-600 text-white text-sm">
                                <option value="">Newest First</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>

                    </form>

                </div>

            </div>

            <div class="col-span-12 md:col-span-9">

                <div class="flex justify-between items-center mb-5">

                    <h2 class="text-lg font-semibold text-gray-300">
                        Product List
                    </h2>

                    <span class="text-sm text-gray-400">
                        Total: {{ $products->total() }}
                    </span>

                </div>

                <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl overflow-hidden">

                    <div class="grid grid-cols-12 bg-white/5 text-gray-300 text-sm p-4">

                        <div class="col-span-2">Category</div>
                        <div class="col-span-3">Name</div>
                        <div class="col-span-2">Description</div>
                        <div class="col-span-2">Price</div>
                        <div class="col-span-3 text-center">Actions</div>

                    </div>

                    @forelse($products as $product)

                    <div class="grid grid-cols-12 p-4 border-b border-white/10 hover:bg-white/5 transition">

                        <div class="col-span-2 text-gray-400 text-sm">
                            {{ $product->category }}
                        </div>

                        <div class="col-span-3 font-semibold text-white">
                            {{ $product->name }}
                        </div>

                        <div class="col-span-2 text-gray-400 text-sm">
                            {{ Str::limit($product->description, 40) }}
                        </div>

                        <div class="col-span-2 font-bold text-green-400">
                            ₹{{ number_format($product->price, 2) }}
                        </div>

                        <div class="col-span-3 flex justify-center gap-2">

                            <a href="/product/{{ $product->id }}/edit"
                                class="bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded-lg text-sm transition">

                                Edit

                            </a>

                            <form action="/product/{{ $product->id }}" method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Delete this product?')"
                                    class="bg-red-500 hover:bg-red-600 px-3 py-2 rounded-lg text-sm transition">

                                    Delete

                                </button>

                            </form>

                        </div>

                    </div>

                    @empty

                    <div class="p-10 text-center text-gray-400">

                        No products found

                    </div>

                    @endforelse

                </div>

                <div class="mt-8 flex justify-center">

                    <div class="bg-white/10 border border-white/20 px-4 py-3 rounded-xl">

                        {{ $products->links() }}

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
        function showSuggestions() {
            var box = document.getElementById('search-suggestions');
            if (box) box.classList.remove('hidden');
        }

        function selectSuggestion(term) {
            document.getElementById('search-input').value = term;
            document.getElementById('search-form').submit();
        }

        document.addEventListener('click', function (e) {
            var box = document.getElementById('search-suggestions');
            var input = document.getElementById('search-input');

            if (box && !box.contains(e.target) && e.target !== input) {
                box.classList.add('hidden');
            }
        });
    </script>

</body>

</html>