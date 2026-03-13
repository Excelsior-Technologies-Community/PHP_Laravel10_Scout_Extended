<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Product</title>

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

    <div class="max-w-xl mx-auto py-14 px-6">

        <div class="bg-white rounded-2xl shadow-xl p-8">

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">
                    Add New Product
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    Create a product that will be indexed by Algolia search
                </p>
            </div>

            <form method="POST" action="/product" class="space-y-5">

                @csrf

                <!-- Product Name -->
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Product Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Enter product name"
                        class="w-full mt-1 border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>


                <!-- Description -->
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="3"
                        placeholder="Enter product description"
                        class="w-full mt-1 border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required></textarea>
                </div>


                <!-- Price -->
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Price (₹)
                    </label>

                    <input
                        type="number"
                        name="price"
                        placeholder="Enter price"
                        class="w-full mt-1 border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>


                <!-- Buttons -->
                <div class="flex items-center gap-4 pt-4">

                    <button
                        class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl shadow hover:scale-105 transition duration-200">
                        Save Product
                    </button>

                    <a
                        href="/"
                        class="text-gray-600 hover:text-gray-900">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>

</html>