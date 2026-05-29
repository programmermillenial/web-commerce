<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.orders.index');
    }

    public function list(Request $request)
    {
        $orders = Order::query()
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest();

        return DataTables()->of($orders)
            ->addColumn('encrypted_code', function ($order) {
                return Crypt::encryptString($order->order_code);
            })
            ->make(true);
    }

    public function show(string $code)
    {
        $order_code = Crypt::decryptString($code);

        $order = Order::with('order_items')
            ->where('order_code', $order_code)
            ->firstOrFail();

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, string $encrypted)
    {
        $request->validate([
            'status' => 'required|in:pending,process,done',
        ]);

        $order_code = Crypt::decryptString($encrypted);

        $order = Order::where('order_code', $order_code)->firstOrFail();

        $order->update([
            'status' => $request->status
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status order berhasil diubah'
        ]);
    }

    public function approvePayment($id)
    {
        DB::beginTransaction();

        try {
            $order = Order::with('customer')->findOrFail($id);

            // hanya bisa approve jika waiting
            if ($order->transaction_status !== 'waiting') {
                return back()->with(
                    'error',
                    'Order ini belum upload bukti pembayaran atau sudah di-approve.'
                );
            }

            // update order
            $order->update([
                'transaction_status' => 'paid',
                'status' => 'process',
                'paid_at' => now(),
            ]);

            // update statistik customer
            if ($order->customer) {
                $order->customer->increment('total_orders');
                $order->customer->increment('total_spent', $order->total);

                $order->customer->update([
                    'last_order_at' => now(),
                ]);
            }

            DB::commit();

            return back()->with(
                'success',
                'Pembayaran berhasil di-approve.'
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with(
                'error',
                'Gagal approve pembayaran: ' . $e->getMessage()
            );
        }
    }
}
