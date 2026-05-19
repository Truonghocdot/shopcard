<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Product;
use App\Models\Category;
use App\Models\News;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = Sitemap::create();

        // Add static pages
        $sitemap->add(Url::create('/'));

        // Add dynamic pages
        Product::all()->each(function (Product $product) use ($sitemap) {
            $sitemap->add(
                Url::create("/san-pham/{$product->slug}")
                    ->setLastModificationDate($product->updated_at ?? $product->created_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8)
            );
        });

        Category::all()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(
                Url::create("/danh-muc/{$category->slug}")
                    ->setLastModificationDate($category->updated_at ?? $category->created_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7)
            );
        });

        News::all()->each(function (News $news) use ($sitemap) {
            $sitemap->add(
                Url::create("/tin-tuc/{$news->slug}")
                    ->setLastModificationDate($news->updated_at ?? $news->created_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.9)
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');
    }
}
