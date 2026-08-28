<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Boletos</title>
    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        .main-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px 15px;
        }

        .ticket {
            border: 2px solid #000;
            border-radius: 12px;
            padding: 12px;
            height: 140px;
        }

        .inner-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ticket-left {
            width: 68%;
            vertical-align: top;
            padding-right: 15px;
        }

        .ticket-right {
            width: 32%;
            text-align: center;
            vertical-align: middle;
            border-left: 2px dashed #ccc;
            padding-left: 15px;
        }

        .brand {
            font-size: 11px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
        }

        .title {
            font-size: 18px;
            font-weight: 900;
            margin: 8px 0;
            color: #0f172a;
        }

        .date {
            font-size: 18px;
            color: #0f172a;
            font-weight: 900;
            margin: 5px 0;
        }

        .folio {
            font-size: 16px;
            font-weight: 900;
            color: #1d4ed8;
            margin-top: 15px;
            letter-spacing: 1px;
        }

        .shape-circulo {
            width: 45px;
            height: 45px;
            border-radius: 25px;
            border: 2px solid #0f172a;
            margin: 0 auto 8px auto;
        }

        .shape-cuadrado {
            width: 45px;
            height: 45px;
            border-radius: 8px;
            border: 2px solid #0f172a;
            margin: 0 auto 8px auto;
        }

        .shape-triangulo {
            width: 0;
            height: 0;
            border-left: 22px solid transparent;
            border-right: 22px solid transparent;
            border-bottom: 40px solid;
            background-color: transparent !important;
            margin: 0 auto 8px auto;
        }

        .valido {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #334155;
            letter-spacing: 1px;
        }
    </style>
</head>

<body>
    <table class="main-table">
        @foreach (array_chunk($boletos, 2) as $row)
            <tr>
                @foreach ($row as $boleto)
                    <td style="width: 50%; padding: 0; vertical-align: top;">
                        <div class="ticket">
                            <table class="inner-table">
                                <tr>
                                    <td class="ticket-left">
                                        <div class="brand">Paseos del Prado</div>
                                        <div class="title">BOLETO DE ACCESO</div>
                                        @php
                                            $fechaParts = explode('-', $boleto['fecha']);
                                            $fechaFormat = count($fechaParts) == 3 ? $fechaParts[2] . '/' . $fechaParts[1] . '/' . $fechaParts[0] : $boleto['fecha'];
                                        @endphp
                                        <div class="date">Fecha: {{ $fechaFormat }}</div>
                                        <div class="folio">FOLIO: #{{ str_pad($boleto['folio'], 5, '0', STR_PAD_LEFT) }}</div>
                                    </td>
                                    <td class="ticket-right">
                                        @if($boleto['figura'] == 'circulo')
                                            <div class="shape-circulo" style="background-color: {{ $boleto['color'] }};"></div>
                                        @elseif($boleto['figura'] == 'cuadrado')
                                            <div class="shape-cuadrado" style="background-color: {{ $boleto['color'] }};"></div>
                                        @elseif($boleto['figura'] == 'triangulo')
                                            <div class="shape-triangulo" style="border-bottom-color: {{ $boleto['color'] }};"></div>
                                        @endif
                                        <div class="valido">VALIDO</div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                @endforeach
                @if(count($row) == 1)
                    <td style="width: 50%;"></td>
                @endif
            </tr>
        @endforeach
    </table>
</body>

</html>