<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart;
        $obj = new Cart();

        $response = [
            'cart' => $cart,
            'total' => $obj->total($cart)
        ];

        return response()->json($response, 200);
    }

    /**
     * Add new item to cart storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'id' => 'required|integer|gt:0',
            'qty' => 'required|integer|gte:1',
        ]);

        $product = Product::findOrFail($validatedData['id']);

        $cart = $user->cart()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'price' => $product->price,
            'qty' => $validatedData['qty'],
            'sub_total' => $product->price * $validatedData['qty'],
        ]);

        return response()->json($cart, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'id' => 'required|integer|gt:0',
            'product_id' => 'required|integer|gt:0',
            'qty' => 'required|integer|gte:1'
        ]);

        $cart = $user->cart()->find($validatedData['id']);
        $product = Product::findOrFail($validatedData['product_id']);

        $cart->fill([
            'product_id' => $product->id,
            'qty' => $validatedData['product_id'],
            'sub_total' => $product->price * $validatedData['qty'],
        ]);

        $cart->save();

        return response()->json($cart, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
