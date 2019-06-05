<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\User;
use App\Product;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;
        return $this->showAll($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image',
            'quantity' => 'required|integer|min:1',
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $data['image'] = '1.jpg';
        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);
        
        return $this->showOne($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $seller, Product $product)
    {

        $rules = [
            'quantity' => 'integer|min:1',
            'image' => 'image',
            'status' => 'in:'.Product::AVAILABLE_PRODUCT.','.Product::UNAVAILABLE_PRODUCT
        ];

        $this->validate($request, $rules);

        $this->checkSeller($seller, $product);
        
        $product->fill($request->only([
            'name',
            'description',
            'quantity'
        ]));

        if($request->has('status')){
            $product->status = $request->status;
            if($product->isAvailable() && $product->categories()->count() == 0){
                return $this->errorResponse('That product become available need that product has at least one categories', 409);
            }
        }

        if($product->isClean()){
            return $this->errorResponse('Need specified data for update product', 422);
        }

        $product->save();

        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);
        $product->delete();
        return $this->showOne($product);
    }

    public function checkSeller(User $seller, Product $product)
    {
        if($seller->id !== $product->seller_id)
            throw new HttpException(422, "Modifyin product is possible only it owner");
            
    }
}
