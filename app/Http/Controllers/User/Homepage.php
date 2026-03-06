<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class Homepage extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('user.index', compact('categories'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('user.product_detail', compact('product'));
    }

    public function test()
    {
        return view('user.product');
    }
}
