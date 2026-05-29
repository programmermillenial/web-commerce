<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ConfirmationController extends Controller
{
    function index()
    {
        return view('web.confirmation');
    }

    function search(Request $request)
    {
        $order = Order::where('order_code', $request->order_code)->firstOrFail();

        return redirect()->route('confirmation.upload-proof', [
            'code' => Crypt::encryptString($order->order_code),
        ]);
    }

    function confirmation(string $code)
    {
        try {
            $orderCode = Crypt::decryptString($code);

            $order = Order::where('order_code', $orderCode)->firstOrFail();
        } catch (\Exception $e) {
            abort(404);
        }

        return view('web.confirmation', compact('order'));
    }

    public function uploadProof(Request $request, string $code)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $orderCode = Crypt::decryptString($code);

            $order = Order::where('order_code', $orderCode)->firstOrFail();
        } catch (\Exception $e) {
            abort(404);
        }

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_payment_proof.' . $file->getClientOriginalExtension();

            $file->move(base_path('../images/payment_proofs'), $filename);

            $order->update([
                'payment_proof' => 'images/payment_proofs/' . $filename,
                'transaction_status' => 'waiting',
            ]);
        }

        return redirect()->route('confirmation.upload-proof', [
            'code' => Crypt::encryptString($order->order_code),
        ])->with('success', 'Bukti transfer berhasil dikirim');
    }

    function invoice(string $code)
    {
        $orderCode = Crypt::decryptString($code);

        $order = Order::with([
            'order_items.menu',
            'customer'
        ])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        $pdf = Pdf::loadView('web.invoice', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Invoice-' . $order->order_code . '.pdf');
    }
}
