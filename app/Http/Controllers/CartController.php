<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\CartCollection;
use Illuminate\Support\Facades\Log;

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
        $cart = CartCollection::collection(Cart::where('user_id', $user->id)->get());

        $response = [
            'cart' => $cart,
            'total' => Cart::total($cart)
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
            'product' => 'required|array',
            'product.*.id' => 'required|integer',
            'product.*.qty' => 'required|integer',
        ]);

        $items = [];

        foreach($validatedData['product'] as $i => $product) {

                $obj = Product::find($product['id']);

                $items[$i] = [
                    'id' => $obj->id,
                    'price' => $obj->price,
                    'qty' => $product['qty'],
                    'sub_total' => $obj->price * $product['qty']
                ];
        }

        if (!($user->cart->first())) {
            $cart = $user->cart()->create([
                'user_id' => $user->id,
                'product' => $items,
                'sub_total' => Cart::total($items),
            ]);
        } else {
            $cart = $user->cart->first();
            foreach ($validatedData['product'] as $k => $v) {
                foreach ($cart->product as $i => $j) {
                    if ($v['id'] == $j['id']) {
                        $j['qty'] = $v['qty'];
                    }

                }
            }
        }

        return response()->json($cart);
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

        if ($product->active) {

            $cart->fill([
                'product_id' => $product->id,
                'qty' => $validatedData['qty'],
                'sub_total' => $product->price * $validatedData['qty'],
            ]);

            $cart->save();

            $obj = new CartCollection(Cart::find($cart->id));

            $response = [
                'cart' => $obj,
            ];

            return response()->json($response, 200);
        }

            $response = [
                'cart' => 'El producto seleccionado ya no esta disponible',
            ];

            return response()->json($response, 404);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'id' => 'required|integer|gte:1',
        ]);

        $cart = $user->cart()->findOrFail($validatedData['id']);
        $cart->delete();

        return response()->json($cart);
    }
}
