@component('mail::message')
# Comprobante de Pago Exitoso

Estimado/a **{{ $data['resident'] }}**,

Nos es grato informarle que su pago ha sido procesado exitosamente. Agradecemos su contribución puntual para el mantenimiento y mejora de nuestras instalaciones.

@component('mail::panel')
## Detalles del Pago
- **Servicio/Concepto:** {{ $data['service'] }}
- **Monto Pagado:** ${{ $data['amount'] }} MXN
- **Fecha:** {{ now()->format('d/m/Y') }}
@endcomponent

Adjunto a este correo encontrará su recibo oficial en formato PDF para su respaldo y control personal.

Si tiene alguna duda o aclaración sobre este movimiento, por favor no dude en contactarnos.

Atentamente,<br>
**Administración Paseos del Prado**

*"Juntos hacemos un mejor lugar para vivir"*
@endcomponent