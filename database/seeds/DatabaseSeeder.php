<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Category;
use App\Product;
use App\Transaction;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userQuantity = 200;
        factory(User::class, $userQuantity)->create();

        $categoryQuantity = 10;
        factory(Category::class, $categoryQuantity)->create();

        $productQuantity = 1000;
        factory(Product::class, $productQuantity)->create()->each(function($product){
            $categories = Category::all()->random(rand(1,5))->pluck('id');
            $product->categories()->attach($categories);
        });

        $transactionQuantity = 100;
        factory(Transaction::class, $transactionQuantity)->create();
    }
}
