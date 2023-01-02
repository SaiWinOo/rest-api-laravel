<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardChartController extends Controller
{
    public function popularProducts()
    {
        $products = Product::withCount('orderCounts')->latest('order_counts_count', 'desc')->take(3)->get();
        return response()->json([
            'products' => ProductResource::collection($products),
            'success' => true,
        ]);
    }

    public function ordersChart()
    {

        $ordersThisYear = Order::query()
            ->whereYear('created_at', date('Y'))
            ->selectRaw('month(created_at) as month')
            ->selectRaw('count(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->values()->toArray() ;
        $orderThisMonth = Order::query()
            ->whereMonth('created_at', date('m'))
            ->selectRaw('day(created_at) as day')
            ->selectRaw('count(*) as count')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('count','day')
            ->values()->toArray();
        $totalSumToday = Order::whereDate('created_at',Carbon::today())->sum('total_cost');
        $totalQuantity = OrderDetail::whereDate('created_at',Carbon::today())->sum('quantity');
        $totalOrder = Order::whereDate('created_at',Carbon::today())->count();
        return response()->json([
            'yearOrders' => $ordersThisYear,
            'monthOrders' => $orderThisMonth,
            'todaySales' => $totalSumToday,
            'todayQuantity' => $totalQuantity,
            'todayOrder' => $totalOrder,
        ]);
    }
}
