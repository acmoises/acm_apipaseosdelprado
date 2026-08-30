<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recibo de Nómina</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .header {
            background-color: #4f46e5;
            color: #ffffff;
            padding: 30px 40px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 40px;
        }

        .info-grid {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        .info-grid td {
            padding: 12px 15px;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-grid .label {
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            font-size: 12px;
            width: 40%;
        }

        .info-grid .value {
            font-weight: bold;
            color: #0f172a;
            font-size: 16px;
        }

        .amount-box {
            background-color: #f1f5f9;
            border-left: 4px solid #4f46e5;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
            border-radius: 4px;
        }

        .amount-box h2 {
            margin: 0;
            font-size: 32px;
            color: #1e293b;
        }

        .amount-box p {
            margin: 5px 0 0 0;
            color: #64748b;
            font-size: 14px;
            text-transform: uppercase;
        }

        .footer {
            background-color: #f8fafc;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
        }

        .qr-code {
            text-align: center;
            margin-top: 20px;
        }

        .qr-code img {
            width: 120px;
            height: 120px;
            border: 4px solid #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .security-badge {
            display: inline-block;
            background-color: #dbeafe;
            color: #1e40af;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            margin-top: 10px;
            letter-spacing: 1px;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(79, 70, 229, 0.03);
            white-space: nowrap;
            z-index: -1;
            font-weight: bold;
        }

        .signatures {
            margin-top: 50px;
            width: 100%;
            border-collapse: collapse;
        }

        .signatures td {
            text-align: center;
            width: 50%;
            vertical-align: bottom;
            padding: 0 20px;
        }

        .signature-line {
            border-bottom: 1px solid #1e293b;
            width: 80%;
            margin: 0 auto 8px auto;
            height: 60px;
        }

        .signature-name {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }

        .signature-title {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="watermark">ACM PASEOS DEL PRADO</div>
    <div class="container">
        <div class="header">
            <h1>Recibo de Pago</h1>
            <p>Comprobante Oficial de Nómina</p>
        </div>

        <div class="content">
            <div class="amount-box">
                <p>Monto Pagado</p>
                <h2>${{ number_format($roster->amount, 2) }} MXN</h2>
            </div>

            <table class="info-grid">
                <tr>
                    <td class="label">Folio de Operación</td>
                    <td class="value">{{ $roster->roster_identifier }}</td>
                </tr>
                <tr>
                    <td class="label">Nombre del Beneficiario</td>
                    <td class="value">{{ mb_strtoupper($roster->name) }}</td>
                </tr>
                <tr>
                    <td class="label">Fecha y Hora de Emisión</td>
                    <td class="value">{{ \Carbon\Carbon::parse($roster->created_at)->format('d/m/Y H:i:s') }}</td>
                </tr>
                <tr>
                    <td class="label">Concepto</td>
                    <td class="value">PAGO DE NÓMINA / HONORARIOS</td>
                </tr>
            </table>

            <div class="qr-code">
                <img src="{{ $result_qr->getDataUri() }}" alt="Código QR de Verificación">
                <br>
                <div class="security-badge">
                    HASH DE SEGURIDAD: {{ $security_hash }}
                </div>
            </div>

            <table class="signatures">
                <tr>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-name">Administración</div>
                        <div class="signature-title">Autorización de Pago</div>
                    </td>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-name">{{ mb_strtoupper($roster->name) }}</div>
                        <div class="signature-title">Firma de Conformidad</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Este documento es un comprobante de pago emitido por la Administracion Paseos del Prado.</p>
            <p>El código QR y el Hash de Seguridad garantizan su autenticidad e integridad.</p>
        </div>
    </div>
</body>

</html>