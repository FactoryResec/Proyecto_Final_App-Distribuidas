<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfica de Productos Más Vendidos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            max-width: 800px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .btn-regresar {
            display: inline-flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .btn-regresar i {
            margin-right: 5px;
        }

        .btn-regresar:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('productos.regreso') }}" class="btn-regresar">
            <i class="fas fa-arrow-left"></i> Regresar
        </a>
        <canvas id="graficaProductosMasVendidos" width="800" height="400"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('graficaProductosMasVendidos').getContext('2d');
        var productosMasVendidos = @json($productosMasVendidos);

        var nombres = productosMasVendidos.map(function(producto) {
            return producto.nombre;
        });

        var ventas = productosMasVendidos.map(function(producto) {
            return producto.noVentas;
        });

        var grafica = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: nombres,
                datasets: [{
                    label: 'Ventas',
                    data: ventas,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Ventas'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Productos'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
