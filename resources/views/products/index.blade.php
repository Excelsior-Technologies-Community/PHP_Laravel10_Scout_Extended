<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Product Search</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">

    <div class="max-w-6xl mx-auto py-12 px-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-10">

            <div>
                <h1 class="text-4xl font-bold text-gray-800">
                    Product Search
                </h1>

                <p class="text-gray-500 mt-1">
                    Powered by Laravel Scout + Algolia
                </p>
            </div>

            <a href="/product/create"
                class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl shadow-lg hover:scale-105 transition duration-200">
                + Add Product
            </a>

        </div>


        <!-- Search Box -->
        <div class="bg-white rounded-xl shadow-md p-4 mb-10">

            <form method="GET" action="/" class="flex items-center gap-3">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search products..."
                    class="flex-1 px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">

                <button
                    class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition">
                    Search
                </button>

            </form>

        </div>


        <!-- Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            @forelse($products as $product)

            <div class="bg-white/90 backdrop-blur rounded-xl shadow-lg p-6 hover:shadow-2xl hover:-translate-y-1 transition duration-300">

                <h3 class="text-xl font-semibold text-gray-800 mb-2">
                    {{ $product->name }}
                </h3>

                <p class="text-gray-500 text-sm mb-4">
                    {{ $product->description }}
                </p>

                <div class="flex items-center justify-between">

                    <span class="text-green-600 text-lg font-bold">
                        ₹{{ number_format($product->price, 2) }}
                    </span>

                    <span class="text-xs bg-gray-100 px-3 py-1 rounded-full text-gray-500">
                        Product
                    </span>

                </div>

            </div>

            @empty

            <div class="col-span-3 text-center py-16">

                <p class="text-gray-500 text-lg">
                    No products found
                </p>

                <p class="text-gray-400 text-sm mt-2">
                    Try searching for another product
                </p>

            </div>

            @endforelse

        </div>

    </div>

</body>

</html>