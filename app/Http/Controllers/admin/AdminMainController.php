<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Catalog;
use App\Models\productImage;
use App\Models\Category;

class AdminMainController extends Controller
{
    public function index() {
        return view('admin.main');
    }

    public function edit_products() {
        return view('admin.editProducts', [
            'products' => Product::all()
        ]);
    }
}
