<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = \Faker\Factory::create();

        $imageLinks = [
            'https://content.rozetka.com.ua/goods/images/preview/304964837.jpg',
            'https://content1.rozetka.com.ua/goods/images/preview/554575514.jpg',
            'https://content2.rozetka.com.ua/goods/images/preview/303492509.jpg',
            'https://content.rozetka.com.ua/goods/images/preview/278202649.jpg?advToken=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3NDk4MTQxNTgsInBheWxvYWQiOiJ7XCJwZ1wiOjEyNDU1NDAyLFwiY1wiOjMyMzUsXCJjdFwiOjEsXCJjcGNcIjo0LFwic2NcIjoyLFwicGxcIjozLFwiY2lkXCI6NDYzNzgzOSxcInBcIjozLFwiZ1wiOjJ9In0.S8KnzRZkEo2V2UmU2QFrOOKIeQ0u9_VSmRU1qLxhcmw&advSource=main',
            'https://content.rozetka.com.ua/goods/images/preview/545119438.jpg',
            'https://content2.rozetka.com.ua/goods/images/preview/436233052.jpg',
            'https://content.rozetka.com.ua/goods/images/preview/461649446.jpg',
            'https://content1.rozetka.com.ua/goods/images/preview/397445583.jpg',
            'https://content2.rozetka.com.ua/goods/images/preview/511777140.jpg?advToken=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3NDk4MTQxNTgsInBheWxvYWQiOiJ7XCJwZ1wiOjM4ODI4ODYxLFwiY1wiOjc5MjkzLFwiY3RcIjoxLFwiY3BjXCI6NCxcInNjXCI6MixcInBsXCI6MyxcImNpZFwiOjQ2Mzc4MzksXCJwXCI6MTQsXCJnXCI6Mn0ifQ.9Qk0Sb2qqV1HyLXSQCm2IQT4xmr4IGX7jSrhP9bx1p0&advSource=main',
            'https://content2.rozetka.com.ua/goods/images/preview/539435233.jpg',
        ];

        $categories = Category::all();
        $categoryIds = $categories->pluck('id')->toArray();

        foreach (range(1, 600) as $index) {
            $service = Product::create([
                'name' => $faker->sentence(5),
                'articule' => strtoupper($faker->unique()->bothify('???####')),
                'description' => $faker->sentence(25),
                'price' => $faker->randomFloat(2, 100, 3000),
                'image_path' => $imageLinks[array_rand($imageLinks)],
            ]);

            $randomCategoryIds = $faker->randomElements($categoryIds, rand(1, 3));
            $service->categories()->attach($randomCategoryIds);
        }
    }
}
