<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-xl mx-auto py-12 px-6">

    <div class="bg-white p-8 rounded-2xl shadow-xl">

        <h1 class="text-3xl font-bold mb-6">
            Edit Product
        </h1>

        <form method="POST" action="/product/{{ $product->id }}">

            @csrf
            @method('PUT')

            <div class="mb-5">

                <label class="block mb-2">
                    Product Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ $product->name }}"
                    class="w-full border rounded-xl px-4 py-3">

                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>

            <div class="mb-5">

                <label class="block mb-2">
                    Category
                </label>

                <input
                    type="text"
                    name="category"
                    list="category-list"
                    value="{{ $product->category }}"
                    class="w-full border rounded-xl px-4 py-3">

                <datalist id="category-list">
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}"></option>
                    @endforeach
                </datalist>

                @error('category')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>

            <div class="mb-5">

                <label class="block mb-2">
                    Description
                </label>

                <textarea
                    name="description"
                    class="w-full border rounded-xl px-4 py-3">{{ $product->description }}</textarea>

                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>

            <div class="mb-5">

                <label class="block mb-2">
                    Price
                </label>

                <input
                    type="number"
                    name="price"
                    value="{{ $product->price }}"
                    class="w-full border rounded-xl px-4 py-3">

                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>

            <div class="flex items-center gap-4">

                <button class="bg-green-500 text-white px-6 py-3 rounded-xl">
                    Update Product
                </button>

                <a href="/" class="text-gray-600">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

</body>

</html>