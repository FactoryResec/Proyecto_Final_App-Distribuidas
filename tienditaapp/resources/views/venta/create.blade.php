<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Venta</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .btn-regresar {
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
        .search-form {
            margin-bottom: 20px;
        }
        .product-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .product-info h4 {
            margin-bottom: 5px;
        }
        .product-info p {
            margin: 0;
        }
        .total-amount {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 20px;
        }
        .product-list-item {
            margin-bottom: 10px;
        }
        .btn-remove-product {
            font-size: 14px;
            padding: 5px 10px;
        }
        .alert {
            margin-top: 20px;
        }
        .product-image {
            max-width: 100px;
            max-height: 100px;
            margin-right: 20px;
        }
        .navbar {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            margin-right: 20px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="{{ route('productos.regreso') }}">Ir al inicio</a>
    </div>
    <div class="container">
        
        <h1 class="mb-4">Realizar Venta</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="search-form">
            <label for="search">Código de Barras:</label>
            <input type="text" id="search" class="form-control" autocomplete="off">
        </div>

        <div id="product-info" style="display: none;">
            <div class="product-info">
                <img id="product-image" class="product-image" src="" alt="Imagen del producto">
                <div>
                    <h4 id="product-name"></h4>
                    <p id="product-price"></p>
                </div>
                <div>
                    <div class="form-group">
                        <label for="quantity">Cantidad:</label>
                        <input type="number" id="quantity" class="form-control" min="1">
                    </div>
                    <button id="add-product" class="btn btn-primary">Agregar Producto</button>
                </div>
            </div>
        </div>

        <h2>Productos Seleccionados</h2>
        <ul id="product-list" class="list-group"></ul>
        
        <div class="total-amount">Total: $<span id="total-amount">0.00</span></div>
        
        <form action="{{ route('generate.pdf') }}" method="POST" id="finalize-sale-form">
            @csrf
            <input type="hidden" name="selectedProducts" id="selectedProducts">
            <input type="hidden" name="totalAmount" id="totalAmount">
            <button type="submit" id="finalize-sale" class="btn btn-success mt-3">Finalizar Compra y Generar PDF</button>
            <button type="button" id="share-button" class="btn btn-info mt-3">Compartir Comprobante</button>
        </form>
        
        <div id="error-message" class="alert alert-danger" style="display: none;"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            let selectedProducts = [];
            let totalAmount = 0;
    
            $('#search').on('input', function() {
                const query = $(this).val();
                if (query.length > 0) {
                    $.ajax({
                        url: '{{ route('product.search') }}',
                        method: 'GET',
                        data: { query: query },
                        success: function(response) {
                            if (response.product) {
                                const product = response.product;
                                $('#product-info').show();
                                if (product.image_url) {
                                    $('#product-image').attr('src', product.image_url);
                                } else {
                                    $('#product-image').attr('src', 'default_image_url.jpg');
                                }
                                $('#product-name').text(product.nombre);
                                $('#product-price').text(`Precio: $${product.precio}`);
                                $('#quantity').val(1);
                                $('#add-product').data('product', product);
                            } else {
                                $('#product-info').hide();
                            }
                        },
                        error: function() {
                            console.error('Error en la búsqueda de productos.');
                        }
                    });
                } else {
                    $('#product-info').hide();
                }
            });
    
            $('#add-product').on('click', function() {
                const product = $(this).data('product');
                const quantity = parseInt($('#quantity').val());

                if (quantity > 0) {
                    if (quantity <= product.stock) {
                        product.quantity = quantity;
                        selectedProducts.push(product);
                        renderProductList();
                        $('#product-info').hide();
                        $('#search').val('');
                        $('#error-message').hide();
                    } else {
                        $('#error-message').text('La cantidad solicitada supera el stock disponible.').show();
                    }
                }
            });
    
            function renderProductList() {
                $('#product-list').empty();
                totalAmount = 0;
                selectedProducts.forEach((product, index) => {
                    const productTotal = product.precio * product.quantity;
                    totalAmount += productTotal;
                    $('#product-list').append(`
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ${product.nombre} - $${product.precio} x ${product.quantity}
                            <button class="btn btn-danger btn-sm" onclick="removeProduct(${index})">Eliminar</button>
                        </li>
                    `);
                });
                $('#total-amount').text(totalAmount.toFixed(2));
    
                // Actualizar los campos ocultos con los datos actualizados
                $('#selectedProducts').val(JSON.stringify(selectedProducts));
                $('#totalAmount').val(totalAmount.toFixed(2));
            }
    
            window.removeProduct = function(index) {
                selectedProducts.splice(index, 1);
                renderProductList();
            }
    
            $('#finalize-sale').on('click', function(e) {
                e.preventDefault();
                // Mostrar datos antes de enviar el formulario
                console.log($('#finalize-sale-form').serialize());
                // Verificar si los campos ocultos tienen los datos correctos
                console.log($('#selectedProducts').val(), $('#totalAmount').val());
                // Enviar el formulario al controlador
                $('#finalize-sale-form').submit();
            });
        });

        $(document).ready(function() {
            // Función para mostrar el botón de compartir comprobante
            function showShareButton() {
                $('#share-button').show();
            }

            // Función para ocultar el botón de compartir comprobante
            function hideShareButton() {
                $('#share-button').hide();
            }

            // Verificar si el PDF se ha generado
            const pdfGenerated = localStorage.getItem('pdfGenerated');
            if (pdfGenerated) {
                showShareButton();
                localStorage.removeItem('pdfGenerated'); // Limpiar la bandera
            }

            // Al hacer clic en el botón de generar PDF
            $('#finalize-sale').on('click', function() {
                // Mostrar el botón de compartir comprobante
                showShareButton();
                // Establecer una bandera en el almacenamiento local para indicar que se ha generado el PDF
                localStorage.setItem('pdfGenerated', 'true');
            });

            // Al recargar la página, ocultar el botón de compartir comprobante
            $(window).on('load', function() {
                hideShareButton();
            });

            // Al hacer clic en el botón de compartir comprobante
            $('#share-button').on('click', function() {
                // Aquí puedes implementar la lógica para compartir el comprobante
                // Por ejemplo, abrir un cuadro de diálogo para compartir por correo electrónico o redes sociales
                alert('Listo! Vamos a mandar un correo');
            });

            $('#share-button').on('click', function() {
                // Redireccionar a la ruta deseada
                window.location.href = "{{ route('ventas.createMail') }}";
            });
        });
    </script>
</body>
</html>
