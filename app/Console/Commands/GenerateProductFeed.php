<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProductFeedService;

class GenerateProductFeed extends Command
{
    protected $signature = 'product-feed:generate {filename? : Output filename relative to public storage disk}';

    protected $description = 'Generate Google Merchant Center feed for all products';

    public function handle(): int
    {
        $fileName = $this->argument('filename') ?? 'product_feed.xml';

        $stats = ProductFeedService::generate($fileName);

        $this->info(sprintf(
            'Google product feed generated: %d exported, %d skipped of %d products.',
            $stats['exported'],
            $stats['skipped'],
            $stats['total']
        ));

        foreach ($stats['paths'] as $path) {
            $this->line(' - ' . $path);
        }

        return self::SUCCESS;
    }
}

