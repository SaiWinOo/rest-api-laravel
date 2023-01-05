<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResourceDashboard;
use App\Mail\OrderShipped;
use App\Mail\OrderShippedMail;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderForDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
//        return Carbon::today()->subMonth(1)->format('m');
        $orders = Order::latest()->
        when(request('date'), function ($q) {
            $q->whereDate('created_at', request('date'));
        })->
        when(request('month'), function ($q) {
            $month = request('month');
            if (request('month') === '01') {
                $q->whereMonth('created_at', Carbon::today()->format('m'));
            } else {
                $q->whereMonth('created_at', Carbon::today()->subMonth($month)->format('m'));
            }
        })
            ->get();
        return response()->json([
            'success' => true,
            'orders' => OrderResourceDashboard::collection($orders),
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
        $order = Order::find($id);
        $order->status = $request->status;
        $order->update();
        if($request->status === 'shipped'){
            Mail::to($order->user()->email)->send(new OrderShippedMail());
        }
        return response()->json([
            'success' => true,
            'message' => 'The Product is ' . $request->status,
        ]);
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
