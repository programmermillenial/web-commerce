<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StoreSetting;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    function index()
    {
        $cart = session()->get('cart');
        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang masih kosong');
        }

        $cartTotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['qty'];
        });

        $cartCount = collect($cart)->sum('qty');

        return view('web.checkout', compact(
            'cart',
            'cartTotal',
            'cartCount'
        ));
    }

    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'delivery_method' => 'required|string',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Keranjang masih kosong.'
            ], 422);
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['qty'];
        });

        $voucher = Voucher::where('code', strtoupper($request->code))->first();

        if (!$voucher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode voucher tidak ditemukan.'
            ], 422);
        }

        if (!$voucher->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Voucher tidak aktif.'
            ], 422);
        }

        if ($voucher->start_date && Carbon::now()->lt($voucher->start_date)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Voucher belum bisa digunakan.'
            ], 422);
        }

        if ($voucher->end_date && Carbon::now()->gt($voucher->end_date)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Voucher sudah expired.'
            ], 422);
        }

        if ($voucher->quota !== null && $voucher->used >= $voucher->quota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kuota voucher sudah habis.'
            ], 422);
        }

        if ($subtotal < $voucher->minimum_order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Minimum order Rp ' . number_format($voucher->minimum_order, 0, ',', '.')
            ], 422);
        }

        $discount = 0;
        $freeShipping = false;

        if ($voucher->type === 'fixed') {
            $discount = min($voucher->value, $subtotal);
        }

        if ($voucher->type === 'percent') {
            $discount = ($subtotal * $voucher->value) / 100;

            if ($voucher->maximum_discount) {
                $discount = min($discount, $voucher->maximum_discount);
            }

            $discount = min($discount, $subtotal);
        }

        if ($voucher->type === 'free_shipping') {
            if ($request->delivery_method !== 'delivery') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Voucher gratis ongkir hanya untuk delivery.'
                ], 422);
            }

            $freeShipping = true;
        }

        session([
            'voucher' => [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'type' => $voucher->type,
                'discount' => $discount,
                'free_shipping' => $freeShipping,
            ]
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Voucher berhasil digunakan.',
            'voucher' => session('voucher'),
        ]);
    }

    public function remove()
    {
        session()->forget('voucher');

        return response()->json([
            'status' => 'success',
            'message' => 'Voucher dihapus.'
        ]);
    }

    function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_whatsapp' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'nullable|string',
            'customer_note' => 'nullable|string',
            'delivery_method' => 'required|in:delivery,pickup',
        ], [
            'customer_name.required' => 'Nama pemesan wajib diisi.',
            'customer_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'delivery_method.required' => 'Metode pengiriman wajib dipilih.',
        ]);

        $cart = session()->get('cart', []);
        $setting = StoreSetting::first();

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        DB::beginTransaction();

        try {
            $taxPercent = $setting->tax_percent ?? 0;
            $servicePercent = $setting->service_percent ?? 0;

            $subtotal = collect($cart)->sum(function ($item) {
                return $item['price'] * $item['qty'];
            });

            $taxAmount = ($taxPercent / 100) * $subtotal;
            $serviceAmount = ($servicePercent / 100) * $subtotal;

            $shippingCost = $request->delivery_method == 'pickup'
                ? 0
                : ($setting->shipping_cost ?? 0);

            /*
        |--------------------------------------------------------------------------
        | VALIDASI ULANG VOUCHER
        |--------------------------------------------------------------------------
        */
            $voucherSession = session()->get('voucher');
            $voucher = null;
            $discountAmount = 0;
            $isFreeShipping = false;

            if ($voucherSession && isset($voucherSession['id'])) {
                $voucher = Voucher::find($voucherSession['id']);

                if ($voucher && $voucher->is_active) {
                    $isValidVoucher = true;

                    if ($voucher->start_date && now()->lt($voucher->start_date)) {
                        $isValidVoucher = false;
                    }

                    if ($voucher->end_date && now()->gt($voucher->end_date)) {
                        $isValidVoucher = false;
                    }

                    if ($voucher->quota !== null && $voucher->used >= $voucher->quota) {
                        $isValidVoucher = false;
                    }

                    if ($subtotal < $voucher->minimum_order) {
                        $isValidVoucher = false;
                    }

                    if ($voucher->type === 'free_shipping' && $request->delivery_method !== 'delivery') {
                        $isValidVoucher = false;
                    }

                    if ($isValidVoucher) {
                        if ($voucher->type === 'fixed') {
                            $discountAmount = min($voucher->value, $subtotal);
                        }

                        if ($voucher->type === 'percent') {
                            $discountAmount = ($subtotal * $voucher->value) / 100;

                            if ($voucher->maximum_discount) {
                                $discountAmount = min($discountAmount, $voucher->maximum_discount);
                            }

                            $discountAmount = min($discountAmount, $subtotal);
                        }

                        if ($voucher->type === 'free_shipping') {
                            $isFreeShipping = true;
                            $shippingCost = 0;
                        }
                    } else {
                        $voucher = null;
                    }
                } else {
                    $voucher = null;
                }
            }

            $total = $subtotal - $discountAmount + $taxAmount + $serviceAmount + $shippingCost;

            if ($total < 0) {
                $total = 0;
            }

            // AUTO CREATE / UPDATE CUSTOMER
            $customer = Customer::updateOrCreate(
                [
                    'phone' => $request->customer_whatsapp,
                ],
                [
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'address' => $request->customer_address,
                    'is_active' => true,
                ]
            );

            $order = Order::create([
                'order_code' => 'ORD-' . date('YmdHis'),
                'customer_id' => $customer->id,
                'customer_name' => $request->customer_name,
                'customer_address' => $request->customer_address,
                'customer_whatsapp' => $request->customer_whatsapp,
                'customer_email' => $request->customer_email,
                'customer_note' => $request->customer_note,
                'delivery_method' => $request->delivery_method,

                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'service_amount' => $serviceAmount,
                'shipping_cost' => $shippingCost,

                'voucher_id' => $voucher?->id,
                'voucher_code' => $voucher?->code,
                'discount_amount' => $discountAmount,

                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($cart as $menuId => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menuId,
                    'menu_name' => $item['name'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);
            }

            if ($voucher) {
                $voucher->increment('used');
            }

            session()->forget('cart');
            session()->forget('voucher');

            DB::commit();

            return redirect()->route('confirmation.upload-proof', [
                'code' => Crypt::encryptString($order->order_code),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('checkout.index')
                ->withInput()
                ->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }
}
