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

        .container {
            width: 100%;
            padding: 10px;
            border: 1px solid #000;
            border-radius: 8px;
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

        /* Cambios en la estructura para que funcione en dompdf */
        .qr-title-container {
            width: 100%;
            margin-bottom: 32px;
            display: table;
        }

        .qr-container {
            display: table-cell;
            width: 20%; /* QR ocupará el 20% del ancho */
            text-align: left;
        }

        .receipt-header {
            display: table-cell;
            width: 75%; /* El resto del espacio lo ocupa el título y los datos */
            text-align: left;
            vertical-align: middle;
        }

        /* Reducir interlineado */
        .receipt-header h1 {
            font-size: 24px;
            font-weight: bold;
            line-height: 1.2; /* Reducir el espacio entre líneas */
            margin: 0; /* Reducir el margen alrededor del h1 */
        }

        .receipt-header h3 {
            font-size: 18px;
            font-weight: bold;
            line-height: 1.2; /* Reducir el espacio entre líneas */
            margin: 4px 0; /* Reducir el margen alrededor del h3 */
        }

        .receipt-header p {
            line-height: 1.2; /* Reducir el espacio entre líneas */
            margin: 2px 0; /* Reducir el margen entre los párrafos */
        }

        .signature-container {
            margin-top: 64px;
        }

        .signature {
            text-align: center;
            float: left;
            width: 48%;
            margin-right: 4%;
        }

        .signature:last-child {
            margin-right: 0;
        }

        .border-t-1 {
            border-top: 1px solid #000;
        }

        /* Ajustes para asegurar que se vean correctamente en la página A4 */
        .two-receipts {
            display: block;
        }

        .receipt {
            width: 100%;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Recibo de pago 1 -->
    <div class="receipt">
        <div class="qr-title-container">
            <div class="qr-container">
                <img src="data:image/png;base64,{{ base64_encode($result_qr->getString()) }}" alt="Código QR" class="w-16">
            </div>
            <div class="receipt-header">
                <h1 class="font-semibold">Administración Paseos del Prado</h1>
                <h3 class="font-semibold">Recibo de Nómina</h3>
                <p><strong>Folio: {{ $roster->id }}</strong></p>
                <p><strong>Identificador de pago:</strong> {{ $roster_identifier }}</p>
            </div>
        </div>

        <table class="table">
            <tr>
                <th>Nombre</th>
                <td>{{ $name }}</td>
            </tr>
            <tr>
                <th>Tipo de pago</th>
                <td>Efectivo</td>
            </tr>
            <tr>
                <th>Fecha</th>
                <td>{{ $roster->created_at }}</td>
            </tr>
            <tr>
                <th>Monto</th>
                <td>{{ number_format($amount, 2) }} {{ config('app.currency', 'MXN') }}</td>
            </tr>
        </table>

        <div class="signature-container">
            <div class="signature">
                <hr class="border-t-1" style="margin-top: 8px;">
                <p>Administración</p>
            </div>
            <div class="signature">
                <hr class="border-t-1" style="margin-top: 8px;">
                <p>Trabajador</p>
            </div>
        </div>
    </div>

    <br><br><br><br><br>

    <!-- Recibo de pago 2 -->
    <div class="receipt">
        <div class="qr-title-container">
            <div class="qr-container">
                <img src="data:image/png;base64,{{ base64_encode($result_qr->getString()) }}" alt="Código QR" class="w-16">
            </div>
            <div class="receipt-header">
                <h1 class="font-semibold">Administración Paseos del Prado</h1>
                <h3 class="font-semibold">Recibo de Nómina</h3>
                <p><strong>Folio: {{ $roster->id }}</strong></p>
                <p><strong>Identificador de pago:</strong> {{ $roster_identifier }}</p>
            </div>
        </div>

        <table class="table">
            <tr>
                <th>Nombre</th>
                <td>{{ $name }}</td>
            </tr>
            <tr>
                <th>Tipo de pago</th>
                <td>Efectivo</td>
            </tr>
            <tr>
                <th>Fecha</th>
                <td>{{ $roster->created_at }}</td>
            </tr>
            <tr>
                <th>Monto</th>
                <td>{{ number_format($amount, 2) }} {{ config('app.currency', 'MXN') }}</td>
            </tr>
        </table>

        <div class="signature-container">
            <div class="signature">
                <hr class="border-t-1" style="margin-top: 8px;">
                <p>Administración</p>
            </div>
            <div class="signature">
                <hr class="border-t-1" style="margin-top: 8px;">
                <p>Trabajador</p>
            </div>
        </div>
    </div>
</body>
</html>
