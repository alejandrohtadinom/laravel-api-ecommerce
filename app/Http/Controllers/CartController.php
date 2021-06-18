<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\CartItemCollection;
use App\Models\CartItem;

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
        $cart = Cart::where('user_id', $user->id)->first();
        if (!$cart) {
            $response = [
                'message' => 'No posees productos en el carrito',
            ];
            return response()->json($response);
        } else {
            $items = CartitemCollection::collection(CartItem::where('cart_id', $cart->id)->get());
            $response = [
                'ìtems' => $items,
                'total' => $cart->cart_sub_total,
            ];
            return response()->json($response);
        }
    }

    /**
     * Add new item to cart storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_item(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart()->first();

        $validatedData = $request->validate([
            'id' => 'required|integer',
            'qty' => 'required|integer',
        ]);

        $product = Product::find($validatedData['id']);

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $user->id,
            ]);
            $cart->items()->create([
                'product_id' => $product->id,
                'qty' => $validatedData['qty'],
                'item_sub_total' => $product->price * $validatedData['qty'],
            ]);
            $cart->cart_sub_total = Cart::total($cart->items()->get()->toArray());
            $cart->save();
            $response = [
                'items' => CartItemCollection::collection(CartItem::where('cart_id', $cart->id)->get()),
                'total' => $cart->cart_sub_total,
            ];
            return response()->json($response);
        }

        $items = $cart->items()->get();

        if (in_array($validatedData['id'], array_column($items->toArray(), 'product_id'))) {
            $key = array_search($validatedData['id'], array_column($items->toArray(), 'product_id'));
            $obj = $cart->items()->find($items[$key]['id']);
            $obj->qty += $validatedData['qty'];
            $obj->item_sub_total = $product->price * $obj->qty;
            $obj->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'qty' => $validatedData['qty'],
                'item_sub_total' => $product->price * $validatedData['qty'],
            ]);
        }

        $cart->cart_sub_total = Cart::total(CartItem::where('cart_id', $cart->id)->get()->toArray());

        $cart->save();

        $response = [
            'items' => CartItemCollection::collection(CartItem::where('cart_id', $cart->id)->get()),
            'total' => $cart->cart_sub_total,
        ];

        return response()->json($response);
    }

    /**
     * Remove the specified item from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function remove_item(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'id' => 'required|integer|gte:1',
            'item_id' => 'required|integer|gte:1',
        ]);

        $cart = $user->cart()->findOrFail($validatedData['id']);
        $item = $cart->items()->find($validatedData['item_id']);
        $item->delete();

        $response = [
            'items' => CartItemCollection::collection(CartItem::where('cart_id', $cart->id)->get()),
            'total' => $cart->cart_sub_total,
        ];

        return response()->json($response);
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
