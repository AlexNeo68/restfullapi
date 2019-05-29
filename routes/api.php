<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('buyers', 'Buyer\BuyerController', ['only' => ['show', 'index']]);
Route::resource('sellers', 'Seller\SellerController', ['only' => ['show', 'index']]);
Route::resource('categories', 'Category\CategoryController', ['except' => ['create', 'edit']]);
Route::resource('products', 'Product\ProductController', ['only' => ['show', 'index']]);
Route::resource('transactions', 'Transaction\TransactionController', ['only' => ['show', 'index']]);
Route::resource('users', 'User\UserController', ['except' => ['create', 'edit']]);