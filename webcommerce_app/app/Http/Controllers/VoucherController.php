<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VoucherController extends Controller
{
    public function index()
    {
        return view('admin.vouchers.index');
    }

    public function list()
    {
        $data = Voucher::query()->latest();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('type', function ($row) {
                return match ($row->type) {
                    'fixed' => '<span class="badge bg-primary">Fixed</span>',
                    'percent' => '<span class="badge bg-success">Percent</span>',
                    'free_shipping' => '<span class="badge bg-warning text-dark">Free Shipping</span>',
                    default => '-',
                };
            })
            ->editColumn('value', function ($row) {
                if ($row->type === 'percent') {
                    return rtrim(rtrim(number_format($row->value, 2), '0'), '.') . '%';
                }

                if ($row->type === 'free_shipping') {
                    return '-';
                }

                return 'Rp ' . number_format($row->value, 0, ',', '.');
            })
            ->editColumn('minimum_order', function ($row) {
                return 'Rp ' . number_format($row->minimum_order, 0, ',', '.');
            })
            ->editColumn('quota', function ($row) {
                return $row->quota ?? 'Unlimited';
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date ? $row->start_date->format('d-m-Y H:i') : '-';
            })
            ->editColumn('end_date', function ($row) {
                return $row->end_date ? $row->end_date->format('d-m-Y H:i') : '-';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="d-flex justify-content-center gap-1">
                        <a href="' . route('vouchers.edit', $row->id) . '" class="btn btn-sm btn-warning">
                            <i class="ri-edit-line"></i>
                        </a>

                        <form action="' . route('vouchers.destroy', $row->id) . '" method="POST" class="delete-form">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['type', 'is_active', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:100|unique:vouchers,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,percent,free_shipping',
            'value' => 'nullable|numeric|min:0',
            'minimum_order' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        Voucher::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->type === 'free_shipping' ? 0 : ($request->value ?? 0),
            'minimum_order' => $request->minimum_order ?? 0,
            'maximum_discount' => $request->maximum_discount,
            'quota' => $request->quota,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil ditambahkan');
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code' => 'required|string|max:100|unique:vouchers,code,' . $voucher->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,percent,free_shipping',
            'value' => 'nullable|numeric|min:0',
            'minimum_order' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $voucher->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->type === 'free_shipping' ? 0 : ($request->value ?? 0),
            'minimum_order' => $request->minimum_order ?? 0,
            'maximum_discount' => $request->maximum_discount,
            'quota' => $request->quota,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil diupdate');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dihapus');
    }
}
