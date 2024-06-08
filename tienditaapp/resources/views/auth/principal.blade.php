<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - TienditaApp</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            background-color: #f3f3f3;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }
        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
        }
        .sidebar-header h2 {
            font-size: 24px;
            color: #ffb700;
            margin: 0 10px;
        }
        .sidebar-header img.circular {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .sidebar a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar a i {
            margin-right: 10px;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 4px;
        }
        .header h1 {
            margin: 0;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-info i {
            margin-right: 10px;
        }
        .logout-btn {
            padding: 8px 16px;
            background-color: #ffb700;
            border: none;
            border-radius: 20px;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-left: 10px;
        }
        .logout-btn:hover {
            background-color: #ff9800;
        }
        .modal {
            display: none; /* Oculto por defecto */
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 4px;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h2 {
            margin: 0;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .low-stock-list {
            list-style-type: none;
            padding: 0;
        }
        .low-stock-list li {
            margin: 10px 0;
        }
        .alert {
            padding: 20px;
            background-color: #f44336; /* Red */
            color: white;
            margin-bottom: 15px;
        }
        .alert.success {background-color: #4CAF50;} /* Green */
        .alert.info {background-color: #2196F3;} /* Blue */
        .alert.warning {background-color: #ff9800;} /* Orange */
        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }
        .closebtn:hover {
            color: black;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('imagesCesar/logoDoncheto.png') }}" alt="Logo de TienditaApp" class="circular">
            <h2>TienditaApp</h2>
        </div>
        <a href="{{ route('ventas.create') }}"><i class="fas fa-cash-register"></i> Venta</a>
        <a href="{{ route('productos.create') }}"><i class="fas fa-boxes"></i>Productos</a>
        <a href="{{ route('proveedores.create') }}"><i class="fas fa-truck"></i> Proveedores</a>
        <a href="{{ route('grafica.productos.mas.vendidos') }}"><i class="fas fa-chart-line"></i> Gráfica</a>
        <a href="{{ route('usuarios.create') }}"><i class="fas fa-users"></i> Usuarios</a>
    </div>
    <div class="content">
        <div class="header">
            <h1>Bienvenido a TienditaApp</h1>
            <!-- Información del usuario -->
    @if(Auth::user() && Auth::user()->name)
    <div class="user-info">
        <i class="fas fa-user-circle fa-2x"></i>
        <div>
            <strong>{{ Auth::user()->name }}</strong><br>
            <small>{{ Auth::user()->email }}</small><br>
            <small>{{ Auth::user()->rol }}</small>
        </div>
    </div>
@else
    <!-- Redireccionar al usuario a la ruta de login -->
    <script>window.location = "{{ route('login') }}";</script>
@endif

            <!-- Botón de cierre de sesión -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="logout-btn" type="submit">Cerrar sesión</button>
            </form>
        </div>
        <!-- Alert message container -->
        @if(session('error'))
        <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
            {{ session('error') }}
        </div>
        @endif
        <div class="text-center">
            <img src="{{ asset('imagesCesar/logoTiendaA.png') }}" alt="Logo de TienditaApp" style="max-width: 100%; height: auto;">
        </div>
        <!-- Modal de notificación de productos con bajo stock -->
        <div id="lowStockModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Productos con bajo stock</h2>
                    <span class="close">&times;</span>
                </div>
                <ul class="low-stock-list" id="lowStockList"></ul>
            </div>
        </div>

        

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Obtener el modal
            var modal = $('#lowStockModal');

            // Obtener el botón que cierra el modal
            var span = $('.close');

            // Cuando el usuario hace clic en el botón de cierre, cierra el modal
            span.on('click', function() {
                modal.hide();
            });

            // Cuando el usuario hace clic en cualquier lugar fuera del modal, lo cierra
            $(window).on('click', function(event) {
                if ($(event.target).is(modal)) {
                    modal.hide();
                }
            });

            // Hacer una solicitud AJAX para obtener los productos con bajo stock
            $.ajax({
                url: '{{ route('products.lowStock') }}',
                method: 'GET',
                success: function(response) {
                    if (response.lowStockProducts && response.lowStockProducts.length > 0) {
                        var lowStockList = $('#lowStockList');
                        lowStockList.empty();
                        response.lowStockProducts.forEach(function(product) {
                            lowStockList.append('<li>' + product.nombre + ' - Stock: ' + product.stock + '</li>');
                        });
                        modal.show();
                    }
                },
                error: function() {
                    console.error('Error al obtener los productos con bajo stock.');
                }
            });
        });
    </script>
</body>
</html>
