<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\User;
use App\Transaction;
use Illuminate\Support\Facades\DB;
use App\Transformers\TransactionTransformer;

class ProductBuyerTransactionController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:purchase-products')->only(['store']);
        $this->middleware('transform.input:' . TransactionTransformer::class)->only(['store']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1'
        ];

        $this->validate($request, $rules);

        if($product->seller_id === $buyer->id){
            return $this->errorResponse('Seller cannot buy from self', 409);
        }

        if(!$buyer->isVerified()){
            return $this->errorResponse('Buyer must be verified', 409);
        }

        if (!$product->seller->isVerified()) {
            return $this->errorResponse('Seller of product must be verified', 409);
        }

        if(!$product->isAvailable()){
            return $this->errorResponse('The product must be available', 409);
        }

        if($product->quantity < $request->quantity){
            return $this->errorResponse('Product quantity is less than you want to buy!', 409);
        }

        return DB::transaction(function () use ($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();
            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id
            ]);
            return $this->showOne($transaction);
        });

    }

}
