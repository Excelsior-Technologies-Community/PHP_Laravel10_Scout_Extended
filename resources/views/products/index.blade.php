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


    <!-- TOP HEADER -->
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


        <!-- ALERT -->
        @if(session('success'))
        <div class="mb-6 bg-green-500/20 border border-green-500 text-green-300 px-5 py-3 rounded-xl">
            {{ session('success') }}
        </div>
        @endif


        <!-- FLOATING SEARCH BOX -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-5 rounded-2xl mb-8">

            <form method="GET" class="flex gap-3">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search products..."
                    class="w-full px-5 py-3 rounded-xl bg-black/30 border border-gray-600 text-white focus:ring-2 focus:ring-indigo-500 outline-none">

                <button class="bg-indigo-500 px-6 rounded-xl hover:bg-indigo-600 transition">
                    Search
                </button>

                @if(request('search'))
                <a href="/" class="bg-gray-700 px-5 py-3 rounded-xl hover:bg-gray-600">
                    Reset
                </a>
                @endif

            </form>

        </div>


        <!-- HEADER -->
        <div class="flex justify-between items-center mb-5">

            <h2 class="text-lg font-semibold text-gray-300">
                Product List
            </h2>

            <span class="text-sm text-gray-400">
                Total: {{ $products->total() ?? count($products) }}
            </span>

        </div>


        <!-- TABLE CARD STYLE -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl overflow-hidden">


            <!-- HEADER -->
            <div class="grid grid-cols-12 bg-white/5 text-gray-300 text-sm p-4">

                <div class="col-span-3">Name</div>
                <div class="col-span-4">Description</div>
                <div class="col-span-2">Price</div>
                <div class="col-span-3 text-center">Actions</div>

            </div>


            <!-- DATA -->
            @forelse($products as $product)

            <div class="grid grid-cols-12 p-4 border-b border-white/10 hover:bg-white/5 transition">

                <!-- NAME -->
                <div class="col-span-3 font-semibold text-white">
                    {{ $product->name }}
                </div>

                <!-- DESC -->
                <div class="col-span-4 text-gray-400 text-sm">
                    {{ Str::limit($product->description, 60) }}
                </div>

                <!-- PRICE -->
                <div class="col-span-2 font-bold text-green-400">
                    ₹{{ number_format($product->price, 2) }}
                </div>

                <!-- ACTIONS -->
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


        <!-- PAGINATION -->
        <div class="mt-8 flex justify-center">

            <div class="bg-white/10 border border-white/20 px-4 py-3 rounded-xl">

                {{ $products->links() }}

            </div>

        </div>

    </div>

</body>

</html>