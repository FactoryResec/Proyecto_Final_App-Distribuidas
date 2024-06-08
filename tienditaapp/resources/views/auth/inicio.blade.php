<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Abarrotes - Inicio</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #007bff;
            color: #fff;
            padding: 40px 0;
            text-align: center;
        }
        .header h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .header h4 {
            font-size: 20px;
            margin-bottom: 30px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        .options {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        .option {
            margin: 0 20px;
        }
        .option a {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 18px;
            transition: background-color 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .option a:hover {
            background-color: #0056b3;
        }
        .image {
            margin-top: 50px;
            text-align: center;
        }
        .image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .info {
            margin-top: 50px;
            text-align: left;
        }
        .info p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .contact {
            margin-top: 50px;
        }
        .contact p {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .contact ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            text-align: center;
        }
        .contact ul li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>¡Tienda de Abarrotes Don cheto el Abarrotero!</h1>
        <h4>Precios competitivos, productos tentativos</h4>
    </div>
    <div class="container">
        <div class="image">
            <img src="{{ asset('imagesCesar/logotienda.png') }}" alt="Logotipo de la tienda de abarrotes">
        </div>
        <div class="options">
            <div class="option">
                <a href="{{ route('login') }}">Iniciar sesión</a>
            </div>
        </div>
        <div class="info">
            <h2>Acerca de nosotros</h2>
            <p>Somos una tienda de abarrotes comprometida con ofrecer productos de alta calidad a precios competitivos. Nuestro objetivo es satisfacer las necesidades de nuestros clientes y brindarles una experiencia de compra excepcional.</p>
        </div>
        <div class="contact">
            <h2>¡Contáctanos!</h2>
            <p>Puedes contactarnos a través de los siguientes medios:</p>
            <ul>
                <li>Email: cesar0in.16@gmail.com</li>
                <li>Email: info@tiendaabarrotessedwin.com</li>
                <li>Teléfono: 7471728136</li>
                <li>Teléfono: (55) 1234 5678</li>
            </ul>
        </div>
    </div>
</body>
</html>
