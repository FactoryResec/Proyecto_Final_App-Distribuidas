<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Venta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }
        .ticket {
            width: 80mm;
            max-width: 80mm;
            margin: 20px auto;
            background: #fff;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 1.5rem;
            color: #333;
        }
        .header p {
            margin: 2px 0;
            font-size: 0.9rem;
            color: #777;
        }
        .products {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .products th, .products td {
            text-align: left;
            padding: 5px;
            border-bottom: 1px solid #ccc;
        }
        .products th {
            background: #f0f0f0;
            color: #333;
            font-weight: bold;
        }
        .products td {
            color: #555;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
            font-size: 1.1rem;
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h2>Don Cheto el Abarrotero</h2>
            <p>Ubicación: Zumpango del Rio</p>
            <p>Teléfono: 123-456-7890</p>
        </div>
        <table class="products">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($selectedProducts as $product)
                    <tr>
                        <td>{{ $product['nombre'] }}</td>
                        <td>{{ $product['quantity'] }}</td>
                        <td>${{ $product['precio'] }}</td>
                        <td>${{ number_format($product['precio'] * $product['quantity'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="total">
            <p>Total: ${{ number_format($totalAmount, 2) }}</p>
        </div>
        <div class="footer">
            <p>¡Gracias por su compra!</p>
            <p>Fecha: {{ \Carbon\Carbon::now('America/Mexico_City')->format('d/m/Y H:i') }}</p>
            <p>Visítanos nuevamente!!</p>
        </div>
    </div>
</body>
</html>
