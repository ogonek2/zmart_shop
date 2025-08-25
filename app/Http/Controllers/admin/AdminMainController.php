<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Catalog;
use App\Models\productImage;
use App\Models\Category;
use App\Models\Orders;

class AdminMainController extends Controller
{
    public function index()
    {
        return view('admin.main');
    }

    public function edit_products()
    {
        $usedProductIds = Product::whereHas('package')->pluck('id')->unique();
        $count = $usedProductIds->count();

        return view('admin.editProducts', [
            'products' => Product::all(),
            'packages' => $usedProductIds,
        ]);
    }
    
    public function orders(){
        return view('admin.ordersTable', ['orders_data' => Orders::all()]);
    }
}
