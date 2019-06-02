<?php

use Faker\Generator as Faker;
use App\Product;
use App\User;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'image' => $faker->randomElement(['1.jpg', '2.jpg', '3.jpg']),
        'status' => $faker->randomElement([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
        'seller_id' => User::all()->random()->id,
        'quantity' => $faker->numberBetween(1, 10)
    ];
});
