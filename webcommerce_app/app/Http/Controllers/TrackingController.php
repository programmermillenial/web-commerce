<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TrackingController extends Controller
{
    function index()
    {
        return view('web.tracking');
    }

    function search(Request $request)
    {
        $order = Order::where('order_code', $request->order_code)->first();

        if (!$order) {
            return back()->with('error', 'Order tidak ditemukan');
        }

        return redirect()->route('tracking.show', [
            'code' => Crypt::encryptString($order->order_code)
        ]);
    }

    public function show(string $code)
    {
        try {

            $orderCode = Crypt::decryptString($code);

            $order = Order::with('order_items')
                ->where('order_code', $orderCode)
                ->firstOrFail();
        } catch (\Exception $e) {

            abort(404);
        }

        return view('web.tracking', compact('order'));
    }
}
