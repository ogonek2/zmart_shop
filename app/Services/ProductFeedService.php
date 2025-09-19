<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductFeedService
{
    public static function generate()
    {
        $products = Product::all();

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
        $xml->addChild('channel');
        $channel = $xml->channel;
        $channel->addChild('title', 'Zmart');
        $channel->addChild('link', 'https://zmart.com.ua/');
        $channel->addChild('description', 'Ми створили Zmart, щоб зробити якісну техніку доступною кожному українцю. Наша мета — забезпечити вас сучасними товарами за розумною ціною з максимально зручним сервісом. ');

        foreach ($products->where('brand', '<>', null) as $product) {
            $item = $channel->addChild('item');
            $item->addChild('g:id', htmlspecialchars($product->articule), 'http://base.google.com/ns/1.0');
            $item->addChild('title', htmlspecialchars($product->name));
            $item->addChild(
                'description',
                preg_replace('/\s+/', ' ', trim(strip_tags(html_entity_decode($product->description))))
            );
            $item->addChild('link', 'https://zmart.com.ua/catalog/' . $product->url);
            $item->addChild('g:image_link', asset($product->image_path), 'http://base.google.com/ns/1.0');
            $item->addChild('g:availability', $availabilityMap[$product->availability] ?? 'out of stock', 'http://base.google.com/ns/1.0');
            $item->addChild('g:price', number_format((float) $product->price, 2, '.', '') . ' UAH', 'http://base.google.com/ns/1.0');
            $item->addChild('g:condition', $conditionMap[$product->condition_item] ?? 'used', 'http://base.google.com/ns/1.0');
            $item->addChild('g:brand', $product->brand ?? 'Unknown', 'http://base.google.com/ns/1.0');
            $item->addChild('g:mpn', $product->articule ?? '0000000000000', 'http://base.google.com/ns/1.0');
            $item->addChild('g:identifier_exists', 'false', 'http://base.google.com/ns/1.0');
        }

        // Преобразуем в DOMDocument для форматирования
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        // Сохраняем красиво отформатированный XML
        Storage::disk('public')->put('product_feed.xml', $dom->saveXML());
    }

}
