<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class indexController extends Controller
{
    public function index() {
        $getProducts = Product::all();

        return view('welcome', [
            'all_products' => $getProducts,
        ]);
    }
}
