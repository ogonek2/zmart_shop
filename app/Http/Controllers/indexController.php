<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Product;

class indexController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function catalog()
    {
        $getProducts = Product::select([
                'id', 'name', 'price', 'discount', 'image_path', 'url', 
                'articule', 'availability', 'is_wholesale', 'wholesale_price', 'wholesale_min_quantity'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(45);
        
        return view('welcome', [
            'getProducts' => $getProducts
        ]);
    }

    public function checkout()
    {
        return view('checkout');
    }

    public function getCities()
    {
        $response = Http::post('https://api.novaposhta.ua/v2.0/json/', [
            'apiKey' => env('NOVA_POSHTA_API_KEY'),
            'modelName' => 'Address',
            'calledMethod' => 'getCities',
        ]);

        $data = $response['data'];

        $formatted = collect($data)->map(function ($city) {
            $type = $city['SettlementTypeDescription'] ?? 'місто';
            $label = $city['Description'] . " ({$type})";
            return [
                'Ref' => $city['Ref'],
                'Description' => $label,
            ];
        });

        return response()->json($formatted);
    }
    public function getWarehouses(Request $request)
    {
        $response = Http::post('https://api.novaposhta.ua/v2.0/json/', [
            'apiKey' => env('NOVA_POSHTA_API_KEY'),
            'modelName' => 'Address',
            'calledMethod' => 'getWarehouses',
            'methodProperties' => [
                'CityRef' => $request->cityRef
            ]
        ]);

        return $response['data'];
    }

}
