<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yajra\DataTables\Facades\DataTables;

class CustomerReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.customer.index');
    }

    public function list(Request $request)
    {
        $dateFrom = $request->date_from;
        $dateTo   = $request->date_to;

        $customers = Customer::query()
            ->withCount([
                'orders as total_orders' => function ($q) use ($dateFrom, $dateTo) {
                    $q->where('transaction_status', 'paid')
                        ->with('order_items.menu');

                    if ($dateFrom && $dateTo) {
                        $q->whereBetween('created_at', [
                            $dateFrom . ' 00:00:00',
                            $dateTo . ' 23:59:59'
                        ]);
                    }
                }
            ])
            ->withSum([
                'orders as total_spent' => function ($q) use ($dateFrom, $dateTo) {
                    $q->where('transaction_status', 'paid');

                    if ($dateFrom && $dateTo) {
                        $q->whereBetween('created_at', [
                            $dateFrom . ' 00:00:00',
                            $dateTo . ' 23:59:59'
                        ]);
                    }
                }
            ], 'total')
            ->withMax([
                'orders as last_order_at' => function ($q) use ($dateFrom, $dateTo) {
                    $q->where('transaction_status', 'paid');

                    if ($dateFrom && $dateTo) {
                        $q->whereBetween('created_at', [
                            $dateFrom . ' 00:00:00',
                            $dateTo . ' 23:59:59'
                        ]);
                    }
                }
            ], 'created_at');

        $totalOrdersAll = (clone $customers)->get()->sum('total_orders');
        $totalSpentAll = (clone $customers)->get()->sum('total_spent');

        return DataTables::of($customers)
            ->addIndexColumn()
            ->addColumn('customer_name', function ($row) {
                return $row->name ?? '-';
            })
            ->addColumn('customer_whatsapp', function ($row) {
                return $row->phone ?? '-';
            })
            ->addColumn('total_spent_format', function ($row) {
                return 'Rp ' . number_format($row->total_spent ?? 0, 0, ',', '.');
            })
            ->addColumn('last_order_format', function ($row) {
                return $row->last_order_at
                    ? date('d M Y H:i', strtotime($row->last_order_at))
                    : '-';
            })
            ->addColumn('menus_bought', function ($row) {
                $menus = [];

                foreach ($row->orders as $order) {
                    foreach ($order->order_items as $item) {
                        $menuName = $item->menu->name ?? $item->menu_name ?? '-';
                        $qty = $item->qty ?? 0;

                        if (!isset($menus[$menuName])) {
                            $menus[$menuName] = 0;
                        }
                        $menus[$menuName] += $qty;
                    }
                }

                if (empty($menus)) {
                    return '-';
                }

                $html = '<ul class="mb-0 ps-3">';
                foreach ($menus as $menuName => $qty) {
                    $html .= '<li>' . e($menuName) . ' <strong>x' . $qty . '</strong></li>';
                }
                $html .= '</ul>';

                return $html;
            })
            ->addColumn('total_spent_raw', function ($customer) {
                return $customer->total_spent ?? 0;
            })
            ->with([
                'total_orders_all' => $totalOrdersAll,
                'total_spent_all' => $totalSpentAll,
            ])
            ->rawColumns(['menus_bought'])
            ->make(true);
    }

    function export(Request $request)
    {
        $dateFrom = $request->date_from;
        $dateTo   = $request->date_to;

        $customers = Customer::query()
            ->with([
                'orders' => function ($q) use ($dateFrom, $dateTo) {
                    $q->where('transaction_status', 'paid')
                        ->with('order_items.menu');

                    if ($dateFrom && $dateTo) {
                        $q->whereBetween('created_at', [
                            $dateFrom . ' 00:00:00',
                            $dateTo . ' 23:59:59'
                        ]);
                    }
                }
            ])
            ->withCount([
                'orders as total_orders' => function ($q) use ($dateFrom, $dateTo) {
                    $q->where('transaction_status', 'paid');

                    if ($dateFrom && $dateTo) {
                        $q->whereBetween('created_at', [
                            $dateFrom . ' 00:00:00',
                            $dateTo . ' 23:59:59'
                        ]);
                    }
                }
            ])
            ->withSum([
                'orders as total_spent' => function ($q) use ($dateFrom, $dateTo) {
                    $q->where('transaction_status', 'paid');

                    if ($dateFrom && $dateTo) {
                        $q->whereBetween('created_at', [
                            $dateFrom . ' 00:00:00',
                            $dateTo . ' 23:59:59'
                        ]);
                    }
                }
            ], 'total')
            ->withMax([
                'orders as last_order_at' => function ($q) use ($dateFrom, $dateTo) {
                    $q->where('transaction_status', 'paid');

                    if ($dateFrom && $dateTo) {
                        $q->whereBetween('created_at', [
                            $dateFrom . ' 00:00:00',
                            $dateTo . ' 23:59:59'
                        ]);
                    }
                }
            ], 'created_at')
            ->orderByDesc('created_at')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Customer Report');

        $headers = [
            'No',
            'Nama Customer',
            'WhatsApp',
            'Email',
            'Alamat',
            'Total Order',
            'Total Spent',
            'Last Order',
            'Menu Dibeli',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $row = 2;
        $no = 1;

        foreach ($customers as $customer) {
            $menus = [];

            foreach ($customer->orders as $order) {
                foreach ($order->order_items ?? [] as $item) {
                    $menuName = $item->menu->name ?? $item->menu_name ?? '-';
                    $qty = $item->qty ?? 0;

                    if (!isset($menus[$menuName])) {
                        $menus[$menuName] = 0;
                    }

                    $menus[$menuName] += $qty;
                }
            }

            $menuText = collect($menus)
                ->map(fn($qty, $name) => $name . ' (' . $qty . 'x)')
                ->implode(', ');

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $customer->name ?? '-');
            $sheet->setCellValue('C' . $row, $customer->phone ?? '-');
            $sheet->setCellValue('D' . $row, $customer->email ?? '-');
            $sheet->setCellValue('E' . $row, $customer->address ?? '-');
            $sheet->setCellValue('F' . $row, $customer->total_orders ?? 0);
            $sheet->setCellValue('G' . $row, $customer->total_spent ?? 0);
            $sheet->setCellValue(
                'H' . $row,
                $customer->last_order_at
                    ? date('d-m-Y H:i', strtotime($customer->last_order_at))
                    : '-'
            );
            $sheet->setCellValue('I' . $row, $menuText ?: '-');

            $row++;
        }

        // ROW TOTAL
        $totalOrders = $customers->sum('total_orders');
        $totalSpent = $customers->sum('total_spent');

        $sheet->setCellValue('A' . $row, '');
        $sheet->setCellValue('B' . $row, '');
        $sheet->setCellValue('C' . $row, '');
        $sheet->setCellValue('D' . $row, '');
        $sheet->setCellValue('E' . $row, 'TOTAL');
        $sheet->setCellValue('F' . $row, $totalOrders);
        $sheet->setCellValue('G' . $row, $totalSpent);
        $sheet->setCellValue('H' . $row, '');
        $sheet->setCellValue('I' . $row, '');

        $lastRow = $row;

        $sheet->getStyle('A1:I1')->applyFromArray([
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

        $sheet->getStyle('A1:I' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->getStyle('A' . $lastRow . ':I' . $lastRow)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFF3CD'],
            ],
        ]);

        $sheet->getStyle('E' . $lastRow)
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $sheet->getStyle('G2:G' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->freezePane('A2');

        $filename = 'customer-report-' . date('YmdHis') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
