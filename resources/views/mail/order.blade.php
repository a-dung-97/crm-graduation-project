</html>
<!DOCTYPE html>
<html>

<head>
</head>

<body>
    <p style="text-align: center;"><span style="font-size: 18pt;"><strong>ĐƠN HÀNG #{{ $order->code }}</strong></span>
    </p>
    <p style="text-align: center;"><span
            style="font-size: 14pt;"><strong>{{ Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</strong></span>
    </p>
    <p><strong>Gửi từ: </strong></p>
    <p><em>{{ $company->name }}</em></p>
    <p><em>Địa chỉ: {{ $company->address }}</em></p>
    <p><em>SĐT: {{ $company->phone_number }}</p>
    <p><strong>Gửi đến:</strong></p>
    <p dir="ltr"><em>{{ $customer->name }}</em></p>
    <p dir="ltr"><em>Địa chỉ: {{ $customer->office_address }}</em></p>
    <p dir="ltr"><em>Email: {{ $customer->email }}</em></p>
    <p dir="ltr"><em>SĐT {{ $customer->phone_number }}</em></p>
    <p><strong>Th&ocirc;ng tin sản phẩm:</strong></p>
    <table style="border-collapse: collapse; width: 100%;" border="1">
        <tbody>
            <tr>
                <th style="width: 4.4984%;">STT</th>
                <th style="width: 40.4942%;">Sản phẩm</th>
                <th style="width: 15.5456%;">Gi&aacute; b&aacute;n</th>
                <th style="width: 12.7429%;">Số lượng</th>
                <th style="width: 13.8641%;">Chiết khấu</th>
                <th style="width: 12.855%;">Thuế</th>
            </tr>
            @foreach ($products as $product)
            <tr>
                <td style="width: 4.4984%;text-align: center;">&nbsp;{{ $loop->index+1 }}</td>
                <td style="width: 40.4942%;text-align: center;">&nbsp;{{ $product->name }}</td>
                <td style="width: 15.5456%;text-align: center;">&nbsp;{{ number_format($product->detail->price,2)  }}
                </td>
                <td style="width: 12.7429%;text-align: center;">&nbsp;{{ $product->detail->quantity }}</td>
                <td style="width: 13.8641%;text-align: center;">&nbsp;{{ $product->detail->discount }}%</td>
                <td style="width: 12.855%;text-align: center;">&nbsp;{{ $product->detail->tax }}%</td>

            </tr>
            @endforeach
        </tbody>
    </table>
    <ul style="list-style-type: circle;">
        <li style="text-align: justify;">Tổng tiền h&agrave;ng: {{ number_format($calculation['subtotal'],2) }}</li>
        <li style="text-align: justify;">Ph&iacute; vận chuyển: {{ number_format($calculation['discount'],2) }}</li>
        <li style="text-align: justify;">Tiền thuế: {{ number_format($calculation['tax'],2) }}</li>
        <li style="text-align: justify;">Ph&iacute; vận chuyển: {{ number_format($order->shipping_fee,2) }}</li>
        <li style="text-align: justify;">Tổng thanh to&aacute;n: {{ number_format($calculation['total'],2)}}</li>
    </ul>
</body>

</html>