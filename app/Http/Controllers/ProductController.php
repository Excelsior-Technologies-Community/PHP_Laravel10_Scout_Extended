<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // SEARCH (name / description)
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // MIN PRICE FILTER
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        // MAX PRICE FILTER
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->latest()->paginate(4);

        return view('products.index', compact('products'));
    }


    public function create()
    {
        return view('products.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:1',
        ]);

        Product::create($request->all());

        return redirect('/')
            ->with('success', 'Product added successfully');
    }


    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }


    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:1',
        ]);

        $product->update($request->all());

        return redirect('/')
            ->with('success', 'Product updated successfully');
    }


    public function destroy(Product $product)
    {
        $product->delete();

        return redirect('/')
            ->with('success', 'Product deleted successfully');
    }
}