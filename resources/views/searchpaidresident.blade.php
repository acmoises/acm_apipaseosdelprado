@extends('layouts.searchpaidresident')

@section('content')
    <div class="max-w-6xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold text-center text-gray-900 mb-6">Pagos</h2>

        <!-- Buscar Residente -->
        <div>
            <label for="resident" class="block text-gray-700 font-medium">Buscar Residente</label>
            <select id="resident" name="resident" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" style="width: 100%" required>
                <option value="" disabled selected>Selecciona un residente...</option>  <!-- Este es el valor inicial vacío -->
            </select>
            @error('resident')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <br><br>

        <!-- Buscar por ID -->
        <div class="mt-6">
            <label for="resident_id_input" class="block text-gray-700 font-medium">Buscar por Número de Marbete</label>
            <div class="flex space-x-4">
                <input type="number" id="resident_id_input" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" placeholder="Ingrese el ID del residente">
                <button id="search_by_id_btn" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">Buscar</button>
            </div>
        </div>

        <br><br>

        <!-- Tabla de pagos --> 
        <div class="overflow-x-auto">
            <table id="payment-table" class="min-w-full bg-white border border-gray-200 rounded-md shadow-md">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-2 text-left whitespace-nowrap">Nombre</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Dirección</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">ID de Tarjeta</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Servicio</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Monto</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Fecha de Pago</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Estatus</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se llenarán los pagos cuando se seleccione un residente -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Mostrar todos los residentes cuando se cargue la página
            let allResidents = @json($residents);

            // Función para llenar el select con todos los residentes
            function loadSelectData(residents) {
                let select = $('#resident');
                select.empty(); // Limpiar el select antes de agregar nuevos datos

                // Agregar el primer valor vacío
                select.append('<option value="" disabled selected>Selecciona un residente...</option>');

                // Agregar los residentes al select
                residents.forEach(function(resident) {
                    select.append(`
                        <option value="${resident.id}">${resident.name} ${resident.paternal_surname} ${resident.maternal_surname}</option>
                    `);
                });
            }

            // Llenar el select con todos los residentes al cargar la página
            loadSelectData(allResidents);

            // Configuración de Select2 para hacer la búsqueda
            $('#resident').select2({
                placeholder: "Buscar residente...",
                minimumInputLength: 2, // Mínimo de caracteres para comenzar la búsqueda
            });

            // Función para cargar los pagos de un residente en la tabla
            function loadPaymentsTable(payments) {
                let tableBody = $('#payment-table tbody');
                tableBody.empty(); // Limpiar la tabla antes de agregar nuevos datos

                if (payments.length === 0) {
                    tableBody.append('<tr><td colspan="10" class="text-center text-gray-500">No se encontraron pagos para este residente.</td></tr>');
                } else {
                    payments.forEach(function(payment) {
                        console.log(payment);
                        // Generamos el enlace al archivo PDF usando el identificador del pago
                        let pdfUrl = `/storage/invoices/invoice-${payment.payment_identifier}.pdf`;
                        let statusText = payment.status === 'PAID' ? 'Pagado' : 'Cancelado';
                        let statusColor = payment.status === 'PAID' ? 'text-green-500' : 'text-red-500';
                        // Lógica condicional para deshabilitar el botón
                        let isDisabled = payment.status === 'CANCELED' ? 'disabled' : '';
                        let buttonClass = payment.status === 'CANCELED' ? 'bg-gray-400 cursor-not-allowed' : 'bg-red-500 hover:bg-red-700';

                        tableBody.append(`
                            
                            <tr class="border-t border-gray-200">
                                <td class="px-4 py-2 whitespace-nowrap">${payment.name} ${payment.paternal_surname} ${payment.maternal_surname}</td>
                                <td class="px-4 py-2 whitespace-nowrap">${payment.address}</td>
                                <td class="px-4 py-2 whitespace-nowrap">${payment.card_id}</td>
                                <td class="px-4 py-2 whitespace-nowrap">${payment.service}</td>
                                <td class="px-4 py-2 whitespace-nowrap">${payment.amount}</td>
                                <td class="px-4 py-2 whitespace-nowrap">${payment.created_at}</td>
                                <td class="px-4 py-2 whitespace-nowrap ${statusColor}">${statusText}</td>
                            </tr>
                            
                        `);
                    });
                }
            }

            // Búsqueda en el select
            $('#resident').on('change', function() {
                let residentId = $(this).val(); // Obtener el id del residente seleccionado
                if (residentId) {
                    // Hacer la solicitud AJAX para obtener los pagos del residente seleccionado
                    $.ajax({
                        url: "{{ route('searchpaidresident.byResident') }}", // Ruta para obtener los pagos por residente
                        type: 'GET',
                        data: { resident_id: residentId },
                        success: function(response) {
                            // Llenar la tabla con los pagos
                            loadPaymentsTable(response.payments);
                        }
                    });
                }
            });

            // Búsqueda por ID con botón
            $('#search_by_id_btn').on('click', function () {
                let residentId = $('#resident_id_input').val().trim();

                if (residentId === '') {
                    alert('Por favor, ingrese un ID de residente.');
                    return;
                }

                $.ajax({
                    url: "{{ route('searchpaidresident.byResident') }}", // Usa la misma ruta
                    type: 'GET',
                    data: { resident_id: residentId },
                    success: function (response) {
                        loadPaymentsTable(response.payments);
                    },
                    error: function () {
                        alert('No se pudo obtener la información del residente.');
                    }
                });
            });

        });
    </script>

@endsection