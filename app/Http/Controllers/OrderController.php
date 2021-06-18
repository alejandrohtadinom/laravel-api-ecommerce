<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = request()->user();
        $order = $user->orders()->get();

        $response = [
            'orders' => $order,
        ];

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart()->first();

        $items = $cart->items()->get();

        if (!$user->profile()->first()) {
            return response()->json([
                'message' => 'debes tener un perfil de facturacion',
            ]);

        } else {

            foreach ($items as $item) {
               $product = Product::find($item->product_id);
               $product->qty_active -= $item->qty;
               $product->save();
            }
        }

        $order = $user->orders()->create([
            'items' => $items,
            'total' => $cart->cart_sub_total,
        ]);

        $cart->delete();

        $response = [
            'order' => $order,
        ];

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $user = request()->user();

        $validatedData = request()->validate(([
            'order_id' => 'required|integer|gte:0',
        ]));

        $order = $user->order->find($validatedData['order_id']);

        $response = [
            'order' => $order,
        ];

        return response()->json($response);
    }
}
