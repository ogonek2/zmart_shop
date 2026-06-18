<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class ProductFeedService
{
    private const GOOGLE_NS = 'http://base.google.com/ns/1.0';

    private const DEFAULT_FILENAME = 'product_feed.xml';

    private const CHANNEL_DESCRIPTION = 'Ми створили Zmart, щоб зробити якісну техніку доступною кожному українцю. Наша мета — забезпечити вас сучасними товарами за розумною ціною з максимально зручним сервісом.';

    /**
     * @return array{total: int, exported: int, skipped: int, paths: array<int, string>}
     */
    public static function generate(?string $fileName = null): array
    {
        $fileName = ltrim($fileName ?? self::DEFAULT_FILENAME, '/');

        $stats = [
            'total' => 0,
            'exported' => 0,
            'skipped' => 0,
            'paths' => [],
        ];

        $xml = new SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?>'
            . '<rss version="2.0" xmlns:g="' . self::GOOGLE_NS . '"></rss>'
        );

        $channel = $xml->addChild('channel');
        $channel->addChild('title', 'Zmart');
        $channel->addChild('link', 'https://zmart.com.ua/');
        $channel->addChild('description', self::CHANNEL_DESCRIPTION);

        Product::query()
            ->orderBy('id')
            ->chunkById(200, function ($products) use ($channel, &$stats) {
                foreach ($products as $product) {
                    $stats['total']++;

                    $payload = self::buildItemPayload($product);
                    if ($payload === null) {
                        $stats['skipped']++;
                        continue;
                    }

                    self::appendItem($channel, $payload);
                    $stats['exported']++;
                }
            });

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        $content = $dom->saveXML();
        $stats['paths'] = self::writeFeed($fileName, $content);

        Log::info('Google product feed generated', $stats);

        return $stats;
    }

    private static function buildItemPayload(Product $product): ?array
    {
        $name = self::cleanText($product->name);
        $url = trim((string) $product->url);

        if ($name === '' || $url === '') {
            return null;
        }

        $price = (float) $product->price;
        if ($price <= 0) {
            return null;
        }

        $imageUrl = $product->getImagePath();
        if ($imageUrl === '' || str_contains($imageUrl, 'no-image')) {
            return null;
        }

        $discount = max(0, (int) ($product->discount ?? 0));
        $salePrice = self::resolveSalePrice($product, $price, $discount);

        $description = self::cleanText($product->description)
            ?: self::cleanText($product->seo_description)
            ?: $name;

        $brand = self::cleanText($product->brand) ?: 'Unknown';
        $articule = self::cleanText($product->articule);

        return [
            'id' => (string) $product->id,
            'title' => $name,
            'description' => $description,
            'link' => 'https://zmart.com.ua/catalog/' . ltrim($url, '/'),
            'image_link' => $imageUrl,
            'availability' => self::resolveAvailability($product->availability),
            'price' => $price,
            'sale_price' => $salePrice,
            'has_discount' => $discount > 0 && $salePrice < $price,
            'condition' => self::resolveCondition($product->condition_item),
            'brand' => $brand,
            'mpn' => $articule !== '' ? $articule : (string) $product->id,
        ];
    }

    private static function appendItem(SimpleXMLElement $channel, array $payload): void
    {
        $item = $channel->addChild('item');
        $namespace = self::GOOGLE_NS;

        $item->addChild('g:id', self::escape($payload['id']), $namespace);
        $item->addChild('title', self::escape($payload['title']));
        $item->addChild('description', self::escape($payload['description']));
        $item->addChild('link', self::escape($payload['link']));
        $item->addChild('g:image_link', self::escape($payload['image_link']), $namespace);
        $item->addChild('g:availability', $payload['availability'], $namespace);

        if ($payload['has_discount']) {
            $item->addChild(
                'g:price',
                self::formatPrice($payload['price']) . ' UAH',
                $namespace
            );
            $item->addChild(
                'g:sale_price',
                self::formatPrice($payload['sale_price']) . ' UAH',
                $namespace
            );
        } else {
            $item->addChild(
                'g:price',
                self::formatPrice($payload['sale_price']) . ' UAH',
                $namespace
            );
        }

        $item->addChild('g:condition', $payload['condition'], $namespace);
        $item->addChild('g:brand', self::escape($payload['brand']), $namespace);
        $item->addChild('g:mpn', self::escape($payload['mpn']), $namespace);
        $item->addChild('g:identifier_exists', 'false', $namespace);
    }

    private static function resolveSalePrice(Product $product, float $price, int $discount): float
    {
        if ($discount <= 0) {
            return round($price, 2);
        }

        return round($price - ($price * $discount / 100), 2);
    }

    private static function resolveAvailability(mixed $availability): string
    {
        $map = [
            'in_stock' => 'in stock',
            'out_of_stock' => 'out of stock',
            'on_order' => 'preorder',
            1 => 'in stock',
            2 => 'out of stock',
            '1' => 'in stock',
            '2' => 'out of stock',
        ];

        return $map[$availability] ?? 'out of stock';
    }

    private static function resolveCondition(mixed $condition): string
    {
        $map = [
            'new' => 'new',
            'used' => 'used',
            'refurbished' => 'refurbished',
            1 => 'new',
            2 => 'used',
            3 => 'refurbished',
            '1' => 'new',
            '2' => 'used',
            '3' => 'refurbished',
        ];

        return $map[$condition] ?? 'new';
    }

    /**
     * @return array<int, string>
     */
    private static function writeFeed(string $fileName, string $content): array
    {
        $paths = array_unique([
            public_path($fileName),
            storage_path('app/public/' . $fileName),
        ]);

        foreach ($paths as $path) {
            $directory = dirname($path);
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            file_put_contents($path, $content);
        }

        return $paths;
    }

    private static function formatPrice(float $price): string
    {
        return number_format($price, 2, '.', '');
    }

    private static function cleanText(?string $text): string
    {
        $text = html_entity_decode(strip_tags((string) $text), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return preg_replace('/\s+/u', ' ', trim($text)) ?? '';
    }

    private static function escape(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
