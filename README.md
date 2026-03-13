# PHP_Laravel10_Scout_Extended

## Introduction

PHP_Laravel10_Scout_Extended is a demonstration project built with **Laravel 10** that showcases how to implement **powerful full-text search functionality** using **Laravel Scout** and **Algolia Scout Extended**.

Laravel Scout provides a simple driver-based solution for adding full-text search capabilities to Eloquent models. By integrating Algolia, developers can perform fast and scalable searches on large datasets.

Scout Extended enhances Laravel Scout by providing advanced features such as zero-downtime indexing, queue-based indexing, and better control over search indexing.

This project demonstrates how to integrate Algolia search into a Laravel application and build a simple product search system.

---

## Project Overview

This project implements a simple **Product Search System** where products can be created and indexed using Algolia.

The application allows users to:

- Add new products
- Store products in a MySQL database
- Automatically index products using Laravel Scout
- Perform fast full-text searches using Algolia
- Display search results in a modern Tailwind CSS interface

The project structure is kept simple so developers can easily understand how Laravel Scout and Algolia work together.

---

## Prerequisites

Before starting, make sure your system has:

- PHP >= 8.1  
- Composer  
- MySQL or SQLite  
- Algolia account ([https://www.algolia.com](https://www.algolia.com))  

After creating an Algolia account, copy these credentials:

- **Application ID**  
- **Admin API Key**  
- **Search API Key**  

---

## Step 1: Create Laravel 10 Project

```bash
composer create-project laravel/laravel PHP_Laravel10_Scout_Extended "10.*"
cd PHP_Laravel10_Scout_Extended
```

---

## Step 2: Install Laravel Scout

```bash
composer require laravel/scout:^10.0
```

Publish Scout config:

```bash
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```
This creates config/scout.php.

---

## Step 3: Install Scout Extended

```bash
composer require algolia/scout-extended
```
Publish configuration (Optional):

```bash
php artisan vendor:publish --provider="Algolia\ScoutExtended\ScoutExtendedServiceProvider"
```

This creates config/scout_extended.php

---

## Step 4: Install Algolia PHP Client

```bash
composer require algolia/algoliasearch-client-php
```

---

## Step 5: Configure Environment Variables

Edit .env file:

```.env
SCOUT_DRIVER=algolia

ALGOLIA_APP_ID=your_app_id
ALGOLIA_SECRET=your_admin_api_key
ALGOLIA_SEARCH_KEY=your_search_api_key
```

---

## Step 6: Configure Database

Edit .env:

```.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel10_scout_extended
DB_USERNAME=root
DB_PASSWORD=
```
Create database: laravel10_scout_extended

Run migrations:

```bash
php artisan migrate
```

---

## Step 7: Create Product Model

```bash
php artisan make:model Product -m
```

### Create Products Migration

Edit migration file: database/migrations/xxxx_create_products_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

Run migration:

```bash
php artisan migrate
```

### Create Product Model

Edit app/Models/Product.php:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;

    protected $fillable = ['name','description','price'];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
        ];
    }
}
```

---

## Step 8: Create Product Controller

```bash
php artisan make:controller ProductController
```

Edit app/Http/Controllers/ProductController.php:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        if ($search) {
            $products = Product::search($search)->get();
        } else {
            $products = Product::all();
        }

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        Product::create($request->all());

        return redirect('/')
            ->with('success','Product added successfully');
    }
}
```

---

## Step 9: Create Routes

Edit routes/web.php:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class,'index']);

Route::get('/product/create', [ProductController::class,'create']);

Route::post('/product', [ProductController::class,'store']);
```

---

## Step 10: Create Blade Files

Create folder: resources/views/products

### index.blade.php

File: resources/views/products/index.blade.php

```blade
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
```

### create.blade.php

File: resources/views/products/create.blade.php

```blade
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
```

---

## Step 11: Import Records to Algolia Index

After creating products in the database, you need to send the records to the **Algolia search index**.

Run the following command:

```bash
php artisan scout:import "App\Models\Product"
```

This command will:

1. Fetch all records from the `products` table
2. Convert them into searchable arrays
3. Send them to the Algolia index

Once the import is complete, your data will be searchable through Algolia.

---

## Step 12: Run the Project

Start the Laravel development server:

```bash
php artisan serve
```

Open your browser and visit:

```
http://127.0.0.1:8000
```

Now you can:

- Add new products
- Store products in the database
- Search products using Algolia full-text search
- View results returned from the Algolia search index

---

## Output

<img width="1919" height="1030" alt="Screenshot 2026-03-13 110435" src="https://github.com/user-attachments/assets/e486f15f-1f7f-4e83-9cbf-a47ed8a1febf" />

<img width="1919" height="1030" alt="Screenshot 2026-03-13 110505" src="https://github.com/user-attachments/assets/3f8c6221-e8c2-447e-be3c-1c57dcca8356" />

<img width="1919" height="1029" alt="Screenshot 2026-03-13 110519" src="https://github.com/user-attachments/assets/69fe147a-98e1-446b-8f09-783ab30423a7" />

---

## Project Structure

```
PHP_Laravel10_Scout_Extended
│
├── app
│   ├── Http
│   │   └── Controllers
│   │       └── ProductController.php
│   │
│   └── Models
│       └── Product.php
│
├── config
│   ├── scout.php
│   
│
├── database
│   └── migrations
│       └── xxxx_xx_xx_create_products_table.php
│
├── resources
│   └── views
│       └── products
│           ├── index.blade.php
│           └── create.blade.php
│
├── routes
│   └── web.php
│
└── .env
```

---

Your PHP_Laravel10_Scout_Extended Project is now ready!


