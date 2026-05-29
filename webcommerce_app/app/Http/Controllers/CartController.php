<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    function index()
    {
        $cart = session()->get('cart', []);

        $cartTotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['qty'];
        });

        $cartCount = collect($cart)->sum('qty');

        return view('web.cart', compact('cart', 'cartTotal', 'cartCount'));
    }

    function add(Request $request)
    {
        $menu = Menu::with(['activePrice' => function ($q) {
            $q->where('is_active', 1);
        }])->findOrFail($request->menu_id);

        $cart = session()->get('cart', []);

        $priceData = $menu->activePrice;
        $normalPrice = $priceData->normal_price ?? 0;
        $promoPrice  = $priceData->promo_price ?? 0;

        // PRIORITAS HARGA PROMO
        $price = ($promoPrice > 0) ? $promoPrice : $normalPrice;

        if (isset($cart[$menu->id])) {
            $cart[$menu->id]['qty']++;
        } else {
            $cart[$menu->id] = [
                "name"  => $menu->name,
                "qty"   => 1,
                "price" => $price,
                "photo" => $menu->thumbnail ?? null
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'message'    => 'Menu added to cart successfully',
            'cart'       => $cart,
            'cart_count' => collect($cart)->sum('qty'),
            'cart_total' => collect($cart)->sum(function ($item) {
                return $item['price'] * $item['qty'];
            }),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'menu_id' => 'required',
            'qty'     => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$request->menu_id])) {
            return response()->json([
                'status'  => false,
                'message' => 'Menu tidak ditemukan di keranjang',
            ], 404);
        }

        $cart[$request->menu_id]['qty'] = $request->qty;

        session()->put('cart', $cart);

        return response()->json([
            'status'     => true,
            'message'    => 'Keranjang berhasil diupdate',
            'cart'       => $cart,
            'cart_count' => collect($cart)->sum('qty'),
            'cart_total' => collect($cart)->sum(function ($item) {
                return $item['price'] * $item['qty'];
            }),
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'menu_id' => 'required',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$request->menu_id])) {
            return response()->json([
                'status'  => false,
                'message' => 'Menu tidak ditemukan di keranjang',
            ], 404);
        }

        unset($cart[$request->menu_id]);

        session()->put('cart', $cart);

        return response()->json([
            'status'     => true,
            'message'    => 'Menu berhasil dihapus dari keranjang',
            'cart'       => $cart,
            'cart_count' => collect($cart)->sum('qty'),
            'cart_total' => collect($cart)->sum(function ($item) {
                return $item['price'] * $item['qty'];
            }),
        ]);
    }
}
