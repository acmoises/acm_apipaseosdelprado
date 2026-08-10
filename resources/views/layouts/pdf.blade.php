<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo de Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Ajustes para página A4 */
        @page {
            size: A4;
            margin: 10mm;
        }

        .container {
            width: 100%; /* Asegura que el contenido ocupe todo el ancho de la página */
            padding: 10px;
            border: 1px solid #000;
            border-radius: 8px;
            margin-bottom: 20px; /* Espacio entre los recibos */
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-bold {
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
        }

        .table th {
            background-color: #f1f1f1;
            border-bottom: 2px solid #ddd;
        }

        .table td {
            border-bottom: 1px solid #ddd;
        }

        .signature-container {
            display: flex;
            justify-content: space-between;
            margin-top: 64px;
        }

        .signature {
            width: 48%;
            text-align: center;
        }

        .border-t-2 {
            border-top: 2px solid #000;
        }

        /* Aseguramos que los dos recibos se acomoden a lo largo de la página */
        .two-receipts {
            display: block; /* Asegura que los dos recibos se acomoden verticalmente */
            height: 100%; /* Ocupar todo el espacio de la página */
        }

        .receipt {
            width: 100%; /* Cada recibo ocupa todo el ancho */
            margin-bottom: 20px; /* Espacio entre los recibos */
            padding: 10px;
        }

        /* Ajustes para que el contenido se divida bien en la página */
        .flex {
            display: flex;
        }

        .qr-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <!-- Contenedor que albergará los dos recibos -->
    <div class="two-receipts">
        <!-- Recibo 1 -->
        <div class="receipt">
            @yield('content')
        </div>

        <!-- Recibo 2 -->
        <div class="receipt">
            @yield('content')
        </div>
    </div>
</body>
</html>
