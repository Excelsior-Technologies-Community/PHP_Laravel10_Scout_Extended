<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SearchLog;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });

            $log = SearchLog::where('keyword', $request->search)->first();

            if ($log) {
                $log->increment('count');
            } else {
                SearchLog::create([
                    'keyword' => $request->search,
                    'count' => 1,
                ]);
            }

            $history = session()->get('search_history', []);

            if (!in_array($request->search, $history)) {
                array_unshift($history, $request->search);
                $history = array_slice($history, 0, 5);
                session()->put('search_history', $history);
            }
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->sort === 'price_low') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_high') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(4)->withQueryString();

        $categories = Product::select('category')->distinct()->pluck('category');

        $trending = SearchLog::orderByDesc('count')->limit(5)->pluck('keyword');

        $searchHistory = session()->get('search_history', []);

        return view('products.index', compact('products', 'categories', 'trending', 'searchHistory'));
    }

    public function create()
    {
        $categories = Product::select('category')->distinct()->pluck('category');

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:1',
            'category' => 'required|max:255',
        ]);

        Product::create($request->all());

        return redirect('/')
            ->with('success', 'Product added successfully');
    }

    public function edit(Product $product)
    {
        $categories = Product::select('category')->distinct()->pluck('category');

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:1',
            'category' => 'required|max:255',
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

    public function clearSearchHistory()
    {
        session()->forget('search_history');

        return redirect('/');
    }
}