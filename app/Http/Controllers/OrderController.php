<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Mail\PurchaseMail;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Expr\Cast\Object_;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $orders = Order::where('user_id',Auth::id())->latest()->get();

        return response()->json([
            'success' => true,
            'orders' => OrderResource::collection($orders),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'totalPrice' => 'required|numeric',
            'products' => 'required',
        ]);
        $order = Order::create([
            'total_cost' => $request->totalPrice,
            'user_id' => Auth::id(),
            'voucher' => uniqid(),
        ]);
        if ($order) {
            foreach (json_decode($request->products) as $product) {
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->quantity = $product->quantity;
                $orderDetail->product_id = $product->id;
                $orderDetail->cost = $product->cost;
                $orderDetail->user_id = Auth::id();
                $orderDetail->save();
            }
        }

        Mail::to(Auth::user()->email)->send(new PurchaseMail($request->totalPrice,$request->tax,$request->shipping,$request->productTotalCost));
        return response()->json([
            'success' => true,
            'message' => 'Thank you for purchasing! Please check your email!',
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
