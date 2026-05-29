@php
    $items = $order->order_items;
@endphp

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sales Detail PDF</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }

        h2,
        h4 {
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .muted {
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 2px;
            vertical-align: top;
        }

        .item-table th,
        .item-table td {
            border: 1px solid #ddd;
            padding: 7px;
        }

        .item-table th {
            background: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .section-title {
            margin-top: 20px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .summary {
            width: 45%;
            margin-left: auto;
            margin-top: 20px;
        }

        .summary td {
            padding: 6px;
            border-bottom: 1px solid #eee;
        }

        .grand-total {
            font-size: 15px;
            font-weight: bold;
            border-top: 2px solid #333;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>SALES DETAIL</h2>
        <div class="muted">{{ $order->order_code }}</div>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <h4>Order Information</h4>
                <table>
                    <tr>
                        <td width="120">Order Code</td>
                        <td>: {{ $order->order_code }}</td>
                    </tr>
                    <tr>
                        <td>Order Date</td>
                        <td>: {{ optional($order->created_at)->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>: {{ ucfirst($order->status) }}</td>
                    </tr>
                    <tr>
                        <td>Payment Status</td>
                        <td>: {{ ucfirst($order->transaction_status) }}</td>
                    </tr>
                    <tr>
                        <td>Delivery Method</td>
                        <td>: {{ ucfirst($order->delivery_method) }}</td>
                    </tr>
                </table>
            </td>

            <td width="50%">
                <h4>Customer Information</h4>
                <table>
                    <tr>
                        <td width="120">Name</td>
                        <td>: {{ $order->customer->name ?? ($order->customer_name ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td>WhatsApp</td>
                        <td>: {{ $order->customer_whatsapp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>: {{ $order->customer_email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>: {{ $order->customer_address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Note</td>
                        <td>: {{ $order->customer_note ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="section-title">Menu Detail</div>

    <table class="item-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Menu</th>
                <th width="10%">Qty</th>
                <th width="20%">Price</th>
                <th width="20%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->menu->name ?? ($item->menu_name ?? '-') }}</td>
                    <td class="text-center">{{ $item->qty }}</td>
                    <td class="text-end">Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                    <td class="text-end">
                        Rp {{ number_format(($item->price ?? 0) * ($item->qty ?? 0), 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No item found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary">
        <tr>
            <td>Subtotal</td>
            <td class="text-end">Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Tax</td>
            <td class="text-end">Rp {{ number_format($order->tax_amount ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Service</td>
            <td class="text-end">Rp {{ number_format($order->service_amount ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Shipping Cost</td>
            <td class="text-end">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Voucher</td>
            <td class="text-end">- Rp {{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr class="grand-total">
            <td>Grand Total</td>
            <td class="text-end">Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>
</body>

</html>
