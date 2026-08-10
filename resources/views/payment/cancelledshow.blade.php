@extends('layouts.dashboard')

@section('content')

    <div class="max-w-6xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold text-center text-gray-900 mb-6">Pagos Cancelados</h2>

        <br><br>

        <!-- Tabla de pagos -->
        <div class="overflow-x-auto">
            <table id="payment-table" class="min-w-full bg-white border border-gray-200 rounded-md shadow-md">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-2 text-left whitespace-nowrap">Nombre</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Apellido Paterno</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Apellido Materno</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Teléfono</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Dirección</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">ID de Tarjeta</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Servicio</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Monto</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Fecha de Pago</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Cancelado Por</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Fecha de Cancelación</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($payments as $payment)
                        <!-- Aquí se llenarán los pagos cuando se seleccione un residente -->
                        <tr class="border-t border-gray-200">
                            <td class="px-4 py-2 whitespace-nowrap">{{$payment->name}}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{$payment->paternal_surname}}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{$payment->maternal_surname}}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{$payment->phone_number}}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{$payment->address}}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{$payment->card_id}}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{$payment->service}}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{$payment->amount}}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{$payment->created_at}}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{$payment->uname . " " . $payment->ulastname}}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{$payment->fechacancelacion}}</
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    </div>
    
@endsection
