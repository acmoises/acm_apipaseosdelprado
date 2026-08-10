<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .boleto {
            display: inline-block;
            width: 8cm;
            height: 8cm;
            border: 1px solid #000;
            margin: 0.20cm;
            position: relative;
            vertical-align: top;
            box-sizing: border-box;
        }

        .figura {
        position: absolute;
        top: 5px;
        right: 10px;
    }

    .circulo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
    }

    .cuadrado {
        width: 100px;
        height: 100px;
    }

    .triangulo {
        width: 0;
        height: 0;
        border-left: 50px solid transparent;
        border-right: 50px solid transparent;
        border-bottom: 100px solid red; /* color dinámico se sobrescribe */
        background: none;
    }

    .contenido {
        padding: 3px;
    }

    .fila {
        width: 100%;
        
    }

    .pagina {
        page-break-after: always;
    }

    .texto {
        text-align: center;
    }
    </style>
</head>
<body>
    @foreach (array_chunk($folios, 6) as $grupo)
        <div class="pagina">
            @foreach (array_chunk($grupo, 2) as $fila)
                <div class="fila">
                    @foreach ($fila as $folio)
                        <div class="boleto">
                            
                            @if ($figura === 'circulo')
                                <div class="figura circulo" style="background-color: {{ $color }}"></div>
                            @elseif ($figura === 'cuadrado')
                                <div class="figura cuadrado" style="background-color: {{ $color }}"></div>
                            @elseif ($figura === 'triangulo')
                                <div class="figura triangulo" style="border-bottom-color: {{ $color }}"></div>
                            @elseif ($figura === 'pentagono')
                                <div class="figura pentagono" style="--color: {{ $color }}"></div>
                            @endif

                            <div class="contenido">
                                <p style="margin-left: 10px; font-size: 20px; margin-bottom: 0px;"><strong>Folio:</strong> {{ $folio }}</p>
                                <p style="margin-left: 10px; font-size: 20px; margin-bottom: 0px; margin-top: 0;"><strong>Fecha:</strong></p>
                                <p style="margin-left: 10px; font-size: 35px; margin-bottom: 0px; margin-top: -5;">{{ $fechaFormateada }}</p>
                                <div class="texto">
                                    <img src="{{ $logoSrc }}" style="width: 190px; height: 100px; margin-bottom: 1px; margin-top:0px">
                                    <p style="font-size: 20px"><strong>Pago por $10.00</strong></p>
                                    <p><strong>A nombre de:</strong>____________________________</p>
                                </div>
                                
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endforeach
</body>
</html>
