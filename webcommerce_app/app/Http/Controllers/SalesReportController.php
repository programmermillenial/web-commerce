<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class SalesReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.sales.index');
    }

    public function list(Request $request)
    {
        $query = Order::query()
            ->with(['order_items.menu', 'customer'])
            ->where('transaction_status', 'paid')
            ->orderBy('created_at', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('delivery_method')) {
            $query->where('delivery_method', $request->delivery_method);
        }

        $summaryQuery = clone $query;

        $summary = [
            'total_orders' => (clone $summaryQuery)->count(),
            'total_sales' => (clone $summaryQuery)->sum('total'),
            'total_tax' => (clone $summaryQuery)->sum('tax_amount'),
            'total_service' => (clone $summaryQuery)->sum('service_amount'),
            'total_subtotal' => (clone $summaryQuery)->sum('subtotal'),
            'total_shipping' => (clone $summaryQuery)->sum('shipping_cost'),
            'total_voucher' => (clone $summaryQuery)->sum('discount_amount'),
            'total_qty' => (clone $summaryQuery)
                ->withSum('order_items', 'qty')
                ->get()
                ->sum('order_items_sum_qty'),
        ];

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('order_date', function ($row) {
                return $row->created_at ? $row->created_at->format('d-m-Y H:i') : '-';
            })
            ->addColumn('customer_name', function ($row) {
                return $row->customer->name ?? $row->customer_name ?? '-';
            })
            ->addColumn('menu_detail', function ($row) {
                if (!$row->order_items || $row->order_items->isEmpty()) {
                    return '-';
                }

                return $row->order_items->map(function ($item) {
                    $menuName = $item->menu->name ?? $item->menu_name ?? '-';
                    return $menuName . ' x ' . $item->qty;
                })->implode('<br>');
            })
            ->addColumn('total_qty', function ($row) {
                return $row->order_items->sum('qty');
            })
            ->editColumn('grand_total', function ($row) {
                return '<strong>Rp ' . number_format($row->total ?? 0, 0, ',', '.') . '</strong>';
            })
            ->addColumn('action', function ($row) {
                $code = Crypt::encryptString($row->order_code);

                return '
                            <a href="' . route('reports.sales.detail', $code) . '"
                                class="btn btn-sm btn-primary">
                                <i class="ri-eye-line me-1"></i> Detail
                            </a>
                        ';
            })
            ->with('summary', $summary)
            ->rawColumns(['menu_detail', 'grand_total', 'action'])
            ->make(true);
    }

    function export(Request $request)
    {
        $query = Order::query()
            ->with(['order_items.menu', 'customer'])
            ->where('transaction_status', 'paid');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('delivery_method')) {
            $query->where('delivery_method', $request->delivery_method);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sales Report');

        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', 'SALES REPORT');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:L2');
        $filterText = 'Filter: ';

        $filterText .= $request->start_date ? 'Start Date: ' . $request->start_date . ' | ' : '';
        $filterText .= $request->end_date ? 'End Date: ' . $request->end_date . ' | ' : '';
        $filterText .= $request->status ? 'Status: ' . $request->status . ' | ' : '';
        $filterText .= $request->delivery_method ? 'Method: ' . $request->delivery_method : '';

        if ($filterText === 'Filter: ') {
            $filterText .= 'All Data';
        }

        $sheet->setCellValue('A2', $filterText);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $headers = [
            'No',
            'Date',
            'Order Code',
            'Customer',
            'Menu Detail',
            'Qty',
            'Subtotal',
            'Tax',
            'Service',
            'Shipping',
            'Voucher',
            'Grand Total',
            'Status',
            'Method',
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '4', $header);
            $col++;
        }

        $sheet->getStyle('A4:L4')->getFont()->setBold(true);
        $sheet->getStyle('A4:L4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row = 5;
        $no = 1;

        $totalQty = 0;
        $totalSubtotal = 0;
        $totalTax = 0;
        $totalService = 0;
        $totalShipping = 0;
        $totalVoucher = 0;
        $totalGrandTotal = 0;

        foreach ($orders as $order) {
            $menuDetail = $order->order_items->map(function ($item) {
                $menuName = $item->menu->name ?? $item->menu_name ?? '-';
                return $menuName . ' x ' . $item->qty;
            })->implode("\n");

            $qty = $order->order_items->sum('qty');
            $subtotal = $order->subtotal ?? 0;
            $tax = $order->tax_amount ?? 0;
            $service = $order->service_amount ?? 0;
            $shipping = $order->shipping_cost ?? 0;
            $voucher = $order->discount_amount ?? 0;
            $grandTotal = $order->total ?? 0;

            $totalQty += $qty;
            $totalSubtotal += $subtotal;
            $totalTax += $tax;
            $totalService += $service;
            $totalShipping += $shipping;
            $totalVoucher += $voucher;
            $totalGrandTotal += $grandTotal;

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, optional($order->created_at)->format('d-m-Y H:i'));
            $sheet->setCellValue('C' . $row, $order->order_code);
            $sheet->setCellValue('D' . $row, $order->customer->name ?? $order->customer_name ?? '-');
            $sheet->setCellValue('E' . $row, $menuDetail);
            $sheet->setCellValue('F' . $row, $qty);
            $sheet->setCellValue('G' . $row, $subtotal);
            $sheet->setCellValue('H' . $row, $tax);
            $sheet->setCellValue('I' . $row, $service);
            $sheet->setCellValue('J' . $row, $shipping);
            $sheet->setCellValue('K' . $row, $voucher);
            $sheet->setCellValue('L' . $row, $grandTotal);
            $sheet->setCellValue('M' . $row, ucfirst($order->status));
            $sheet->setCellValue('N' . $row, ucfirst($order->delivery_method));

            $sheet->getStyle('E' . $row)->getAlignment()->setWrapText(true);
            $row++;
        }

        $totalRow = $row;

        $sheet->setCellValue('A' . $totalRow, 'TOTAL');
        $sheet->mergeCells('A' . $totalRow . ':E' . $totalRow);
        $sheet->setCellValue('F' . $totalRow, $totalQty);
        $sheet->setCellValue('G' . $totalRow, $totalSubtotal);
        $sheet->setCellValue('H' . $totalRow, $totalTax);
        $sheet->setCellValue('I' . $totalRow, $totalService);
        $sheet->setCellValue('J' . $totalRow, $totalShipping);
        $sheet->setCellValue('K' . $totalRow, $totalVoucher);
        $sheet->setCellValue('L' . $totalRow, $totalGrandTotal);

        $sheet->getStyle('A' . $totalRow . ':N' . $totalRow)->getFont()->setBold(true);

        $sheet->getStyle('A4:N' . $totalRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle('A4:N' . $totalRow)
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle('A4:N4')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '198754'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->getStyle('A4:A' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F4:F' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('M4:N' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('G5:L' . $totalRow)
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        foreach (range('A', 'N') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->getColumnDimension('E')->setWidth(35);

        $sheet->freezePane('A5');

        $filename = 'sales-report-' . date('YmdHis') . '.xlsx';

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    function detail(string $code)
    {
        $order_code = Crypt::decryptString($code);

        $order = Order::with(['order_items.menu', 'customer'])->where('order_code', $order_code)->firstOrFail();
        return view('admin.reports.sales.detail', compact('order'));
    }

    public function exportPDF(string $code)
    {
        $order_code = Crypt::decryptString($code);

        $order = Order::with(['order_items.menu', 'customer'])
            ->where('order_code', $order_code)
            ->firstOrFail();

        $pdf = Pdf::loadView('admin.reports.sales.pdf', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('sales-detail-' . $order->order_code . '.pdf');
    }
}
