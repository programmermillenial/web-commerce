<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            margin-bottom: 20px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
        }

        .invoice-info,
        .customer-info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table th {
            background: #f5f5f5;
        }

        .text-end {
            text-align: right;
        }

        .total-box {
            margin-top: 20px;
            width: 300px;
            float: right;
        }

        .total-box table td {
            border: none;
            padding: 4px;
        }

        .grand-total {
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="title">INVOICE</div>

        <p>
            <strong>No Invoice:</strong> {{ $order->order_code }}<br>
            <strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}
        </p>
    </div>

    <div class="customer-info">
        <strong>Customer:</strong><br>
        {{ $order->customer_name }}<br>
        {{ $order->customer_whatsapp }}<br>
        {{ $order->customer_email }}<br>
        {{ $order->customer_address }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th width="80">Qty</th>
                <th width="120">Harga</th>
                <th width="140">Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($order->order_items as $item)
                <tr>
                    <td>{{ $item->menu->name ?? '-' }}</td>
                    <td class="text-end">{{ $item->qty }}</td>
                    <td class="text-end">
                        Rp {{ number_format($item->price, 0, ',', '.') }}
                    </td>
                    <td class="text-end">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <table>
            <tr>
                <td>Subtotal</td>
                <td class="text-end">
                    Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                </td>
            </tr>

            <tr>
                <td>Pajak</td>
                <td class="text-end">
                    Rp {{ number_format($order->tax_amount, 0, ',', '.') }}
                </td>
            </tr>

            <tr>
                <td>Service</td>
                <td class="text-end">
                    Rp {{ number_format($order->service_amount, 0, ',', '.') }}
                </td>
            </tr>

            <tr>
                <td>Ongkir</td>
                <td class="text-end">
                    Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                </td>
            </tr>

            <tr>
                <td>Voucher</td>
                <td class="text-end">
                    - Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
                </td>
            </tr>

            <tr class="grand-total">
                <td>Total</td>
                <td class="text-end">
                    Rp {{ number_format($order->total, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
