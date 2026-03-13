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