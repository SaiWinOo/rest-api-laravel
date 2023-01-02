<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Testing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Util\Test;

class TestingController extends Controller
{
    public function store(Request $request)
    {
//        $order =  Order::create([
//            'total_cost' => $request->totelPrice,
//            'customer_id' => Auth::id(),
//        ]);
        return $request->products;

        return response()->json([
            'success' => true,
        ]);
    }
}
