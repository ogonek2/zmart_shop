<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class XmlGenerator implements ToCollection, WithHeadingRow
{
     protected $cache = [];
    /**
    * @param Collection $collection
    */
    
    public function collection(Collection $collection)
    {
        $articules = $collection->pluck('articule')->filter()->unique();
    
        $products = Product::whereIn('articule', $articules)->get()->keyBy('articule');
    
        $availabilityMap = [
            1 => 'in stock',
            2 => 'out of stock',
        ];
    
        $conditionMap = [
            1 => 'new',
            2 => 'used',
            3 => 'refurbished',
        ];
    
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss></rss>');
        $xml->addAttribute('version', '2.0');
        $channel = $xml->addChild('channel');
        $channel->addChild('title', 'Zmart');
        $channel->addChild('link', 'https://zmart.com.ua/');
        $channel->addChild('description', '...');
    
        foreach ($collection as $row) {
            $articule = $row['articule'];
            if (!$articule || !isset($products[$articule])) continue;
    
            $product = $products[$articule];
    
            $item = $channel->addChild('item');
            $item->addChild('g:id', htmlspecialchars($product->id), 'http://base.google.com/ns/1.0');
            $item->addChild('title', htmlspecialchars($product->name));
            $item->addChild('description', preg_replace('/\s+/', ' ', trim(strip_tags(html_entity_decode($product->description)))));
            $item->addChild('link', 'https://zmart.com.ua/catalog/' . $product->url);
            $item->addChild('g:image_link', asset($product->image_path), 'http://base.google.com/ns/1.0');
            $item->addChild('g:availability', $availabilityMap[$product->availability] ?? 'out of stock', 'http://base.google.com/ns/1.0');
            $item->addChild('g:price', number_format((float) $product->price, 2, '.', '') . ' UAH', 'http://base.google.com/ns/1.0');
            $item->addChild('g:condition', $conditionMap[$product->condition_item] ?? 'used', 'http://base.google.com/ns/1.0');
            $item->addChild('g:brand', $product->brand ?? 'Unknown', 'http://base.google.com/ns/1.0');
            $item->addChild('g:mpn', $product->articule ?? '0000000000000', 'http://base.google.com/ns/1.0');
            $item->addChild('g:identifier_exists', 'false', 'http://base.google.com/ns/1.0');
        }
    
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
    
        $filename = 'products_' . now()->format('Ymd_His') . '_' . uniqid() . '.xml';
        Storage::disk('public')->put($filename, $dom->saveXML());
    }

    
    public function chunkSize(): int
    {
        return 100; // обрабатываем по 100 строк за один раз
    }
}
