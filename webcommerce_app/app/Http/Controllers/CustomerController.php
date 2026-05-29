<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.customers.index');
    }

    public function list()
    {
        $customers = Customer::query()->latest();

        return DataTables::of($customers)
            ->addIndexColumn()
            ->editColumn('email', function ($customer) {
                return $customer->email ?? '-';
            })
            ->editColumn('total_orders', function ($customer) {
                return $customer->total_orders ?? 0;
            })
            ->editColumn('total_spent', function ($customer) {
                return 'Rp ' . number_format($customer->total_spent ?? 0, 0, ',', '.');
            })
            ->addColumn('encrypted_url', function ($customer) {
                return route('customers.show', Crypt::encryptString($customer->id));
            })
            ->make(true);
    }

    public function show($id)
    {
        try {
            $customerId = Crypt::decryptString($id);
        } catch (\Exception $e) {
            abort(404);
        }

        $customer = Customer::with(['orders' => function ($q) {
            $q->latest();
        }])->findOrFail($customerId);

        $menuSummary = OrderItem::selectRaw('
                                                menu_id,
                                                menu_name,
                                                SUM(qty) as total_qty,
                                                SUM(subtotal) as total_subtotal
                                            ')
            ->whereHas('order', function ($q) use ($customerId) {
                $q->where('customer_id', $customerId);
            })
            ->groupBy('menu_id', 'menu_name')
            ->orderByDesc('total_qty')
            ->get();

        return view('admin.customers.show', compact('customer', 'menuSummary'));
    }
}
