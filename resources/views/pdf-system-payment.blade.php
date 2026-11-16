<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nota de Remisión {{ $folio }} - SoftMinds</title>
    <style>
        @page {
            margin: 28px 36px;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.45;
        }

        .top-accent {
            height: 6px;
            background-color: #1d4ed8;
            border-radius: 3px;
            margin-bottom: 22px;
        }

        /* Header Layout */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .brand-wrap {
            width: 56%;
        }

        .brand-inner {
            border-collapse: collapse;
        }

        .brand-inner td {
            vertical-align: middle;
            padding: 0;
        }

        .logo-box {
            width: 58px;
            height: 58px;
            padding-right: 14px;
        }

        .brand-name {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin: 0;
            line-height: 1.05;
        }

        .brand-name span {
            color: #2563eb;
        }

        .brand-tagline {
            font-size: 9.5px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            margin-top: 4px;
        }

        /* Folio & Date Box */
        .meta-wrap {
            width: 44%;
            text-align: right;
        }

        .doc-badge {
            display: inline-block;
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            padding: 5px 14px;
            border-radius: 6px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .meta-box {
            width: 230px;
            margin-left: auto;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            border-collapse: collapse;
            overflow: hidden;
        }

        .meta-box th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 5px 8px;
            text-align: center;
            border-right: 1px solid #334155;
        }

        .meta-box th:last-child {
            border-right: none;
        }

        .meta-box td {
            background-color: #f8fafc;
            color: #0f172a;
            font-size: 11.5px;
            font-weight: 700;
            padding: 6px 8px;
            text-align: center;
            border-top: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
        }

        .meta-box td:last-child {
            border-right: none;
        }

        .folio-value {
            color: #1d4ed8 !important;
            font-size: 13px !important;
            font-weight: 800 !important;
            letter-spacing: 0.5px;
        }

        /* Client Info Card */
        .section-label {
            font-size: 10px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 6px;
        }

        .client-card {
            width: 100%;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            border-radius: 10px;
            border-collapse: collapse;
            margin-bottom: 22px;
        }

        .client-card td {
            padding: 11px 16px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .client-card tr:last-child td {
            border-bottom: none;
        }

        .field-caption {
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.6px;
            margin-bottom: 2px;
        }

        .field-value {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .status-pill {
            display: inline-block;
            background-color: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 10px;
            border-radius: 12px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
        }

        .items-table thead th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 10px 12px;
            text-align: left;
            border-right: 1px solid #1e293b;
        }

        .items-table thead th:last-child {
            border-right: none;
        }

        .items-table tbody td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            vertical-align: top;
            font-size: 12px;
            color: #1e293b;
        }

        .items-table tbody td:last-child {
            border-right: none;
        }

        .items-table tbody tr.empty-row td {
            height: 18px;
            padding: 6px 12px;
            background-color: #ffffff;
        }

        .ref-code {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 700;
            font-size: 11.5px;
            color: #1d4ed8;
            background-color: #eff6ff;
            padding: 3px 6px;
            border-radius: 4px;
            display: inline-block;
        }

        .item-title {
            font-weight: 800;
            color: #0f172a;
            font-size: 12.5px;
            margin-bottom: 4px;
        }

        .item-sub {
            font-size: 11px;
            color: #475569;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        /* Summary & Totals Section */
        .bottom-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .bottom-grid td {
            vertical-align: top;
        }

        .notes-col {
            width: 56%;
            padding-right: 20px;
        }

        .totals-col {
            width: 44%;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
        }

        .totals-table td {
            padding: 8px 14px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
        }

        .totals-table .label-cell {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 700;
            text-align: right;
            width: 50%;
            border-right: 1px solid #cbd5e1;
        }

        .totals-table .val-cell {
            background-color: #ffffff;
            color: #0f172a;
            font-weight: 700;
            text-align: right;
            width: 50%;
        }

        .totals-table tr.grand-total td {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 15px;
            font-weight: 800;
            padding: 11px 14px;
            border-bottom: none;
        }

        .totals-table tr.grand-total .val-cell {
            color: #60a5fa;
        }

        /* Verification & Signatures */
        .verification-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
        }

        .verification-inner {
            width: 100%;
            border-collapse: collapse;
        }

        .verification-inner td {
            vertical-align: middle;
        }

        .qr-img {
            width: 68px;
            height: 68px;
            border: 2px solid #ffffff;
            border-radius: 6px;
        }

        .hash-badge {
            display: inline-block;
            margin-top: 4px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 9.5px;
            font-weight: 700;
            color: #1e40af;
            background-color: #dbeafe;
            padding: 2px 7px;
            border-radius: 4px;
        }

        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 42px;
        }

        .signatures-table td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 24px;
        }

        .sig-line {
            border-bottom: 1.5px solid #334155;
            height: 36px;
            margin-bottom: 6px;
        }

        .sig-title {
            font-size: 11px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
        }

        .sig-subtitle {
            font-size: 9.5px;
            color: #64748b;
        }

        .footer {
            margin-top: 28px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="top-accent"></div>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="brand-wrap">
                <table class="brand-inner">
                    <tr>
                        <td class="logo-box">
                            <img src="{{ $logo_data_uri }}" alt="SoftMinds Logo" style="width: 56px; height: 56px;">
                        </td>
                        <td>
                            <h1 class="brand-name">Soft<span>Minds</span></h1>
                            <div class="brand-tagline">Consultoría &amp; Desarrollo de Software</div>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="meta-wrap">
                <div class="doc-badge">Nota de Remisión</div>
                <table class="meta-box">
                    <tr>
                        <th colspan="3">N° de Nota</th>
                    </tr>
                    <tr>
                        <td colspan="3" class="folio-value">{{ $folio }}</td>
                    </tr>
                    <tr>
                        <th>Día</th>
                        <th>Mes</th>
                        <th>Año</th>
                    </tr>
                    <tr>
                        <td>{{ $day }}</td>
                        <td>{{ $month_num }}</td>
                        <td>{{ $year_num }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Client Data -->
    <div class="section-label">Datos del Cliente</div>
    <table class="client-card">
        <tr>
            <td style="width: 58%;">
                <div class="field-caption">Nombre / Cliente</div>
                <div class="field-value">{{ $client_name }}</div>
            </td>
            <td style="width: 42%;">
                <div class="field-caption">Teléfono / Celular</div>
                <div class="field-value">{{ $client_phone }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field-caption">Proyecto / Instalación</div>
                <div class="field-value" style="font-size: 12px;">Administración Residencial Paseos del Prado</div>
            </td>
            <td>
                <div class="field-caption">Estatus de Licencia</div>
                <div>
                    <span class="status-pill">PAGADO &bull; ACTIVO</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Concept / Items Table -->
    <div class="section-label">Detalle de Conceptos</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 16%;">Referencia</th>
                <th style="width: 11%;" class="text-center">Unidades</th>
                <th style="width: 43%;">Descripción</th>
                <th style="width: 15%;" class="text-right">Precio / U.</th>
                <th style="width: 15%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <span class="ref-code">{{ $reference_code }}</span>
                </td>
                <td class="text-center" style="font-weight: 700;">1</td>
                <td>
                    <div class="item-title">LICENCIA DE SOFTWARE PARA ADMINISTRACIÓN PASEOS DEL PRADO</div>
                    <div class="item-sub">
                        <strong>Periodo Cubierto:</strong> {{ $period_label }}
                        @if(!empty($payment_notes))
                            <br><strong>Método / Ref. de Pago:</strong> {{ $payment_notes }}
                        @endif
                    </div>
                </td>
                <td class="text-right" style="font-weight: 700;">${{ $formatted_amount }}</td>
                <td class="text-right" style="font-weight: 800;">${{ $formatted_amount }}</td>
            </tr>
            <tr class="empty-row">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr class="empty-row">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr class="empty-row">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>

    <!-- QR Verification & Totals -->
    <table class="bottom-grid">
        <tr>
            <td class="notes-col">
                <div class="verification-box">
                    <table class="verification-inner">
                        <tr>
                            <td style="width: 78px;">
                                <img src="{{ $qr_data_uri }}" alt="QR Verificación" class="qr-img">
                            </td>
                            <td style="padding-left: 8px;">
                                <div style="font-size: 10.5px; font-weight: 800; color: #0f172a; text-transform: uppercase;">
                                    Comprobante Digital Verificado
                                </div>
                                <div style="font-size: 10px; color: #475569; margin-top: 2px;">
                                    Emitido por <strong>SoftMinds</strong>. Este documento respalda la vigencia del servicio para el periodo <strong>{{ $period_label }}</strong>.
                                </div>
                                <div class="hash-badge">SELLO: {{ $security_hash }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td class="totals-col">
                <table class="totals-table">
                    <tr>
                        <td class="label-cell">Subtotal</td>
                        <td class="val-cell">${{ $formatted_amount }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell">IVA</td>
                        <td class="val-cell">Incluido</td>
                    </tr>
                    <tr class="grand-total">
                        <td class="label-cell" style="background-color: #0f172a; color: #cbd5e1;">TOTAL</td>
                        <td class="val-cell">${{ $formatted_amount }} MXN</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Signatures -->
    <table class="signatures-table">
        <tr>
            <td>
                <div class="sig-line"></div>
                <div class="sig-title">Recibí de Conformidad</div>
                <div class="sig-subtitle">SoftMinds &bull; Consultoría de Software</div>
            </td>
            <td>
                <div class="sig-line"></div>
                <div class="sig-title">{{ $client_name }}</div>
                <div class="sig-subtitle">Cliente / Administración Paseos del Prado</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        <strong>SoftMinds</strong> &mdash; Soluciones Tecnológicas y Desarrollo de Software a Medida &bull; Comprobante Oficial {{ $folio }}
    </div>
</body>
</html>

