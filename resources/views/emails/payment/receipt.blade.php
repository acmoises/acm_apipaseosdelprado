@component('mail::message')
# Comprobante de Pago

Estimado/a {{ $data['resident'] }},

Gracias por su pago de **${{ $data['amount'] }}** correspondiente al servicio **{{ $data['service'] }}**.

Se adjunta su comprobante de pago en formato PDF.
 
{{ config('app.name') }}

"Juntos hacemos un mejor lugar para vivir" 
@endcomponent