<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lista de Productos</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            height: 100%;
        }
        .card-img-top {
            height: 250px; /* Tamaño fijo para las imágenes */
            object-fit:contain; /* Ajustar la imagen sin distorsionarla */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Lista de Productos</h1>
        <div class="row mb-3">
            <div class="col">
                <a href="{{ route('productos.create') }}" class="btn btn-primary">Agregar Nuevo Producto</a>
            </div>
        </div>
        <div class="row">
            @foreach($productos as $producto)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm" >
                    @if(isset($producto->image))
                    <img src="{{ asset('storage/' . $producto->image) }}" class="card-img-top" alt="{{ $producto->nombre }}">
                    @else
                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Placeholder">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $producto->nombre }}</h5>
                        <p class="card-text">{{ $producto->descripcion }}</p>
                        <p class="card-text">Precio: {{ $producto->precio }}</p>
                        <p class="card-text">Stock: {{ $producto->stock }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-sm btn-outline-secondary">Modificar</a>
                                <form action="{{ route('productos.destroy', $producto->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
