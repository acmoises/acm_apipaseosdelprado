@extends('layouts.dashboard')

@section('content')
    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold text-center text-gray-900 mb-6">Registrar Pago</h2>

        <form action="{{ route('payment.store') }}" method="POST">
            @csrf
            @method('POST')

            <div class="space-y-4">

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

                <!-- ID del Residente -->
                <div>
                    <!-- <label for="resident_id" class="block text-gray-700 font-medium">ID del Residente</label> -->
                    <input type="hidden" id="resident_id" name="resident_id" value="{{ old('resident_id') }}" placeholder="Ingresa el ID del residente" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                    @error('resident_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nombre Residente -->

                <input type="hidden" name="name" id="name" value="" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal">
                <input type="hidden" name="paternal_surname" id="paternal_surname" value="" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal">
                <input type="hidden" name="maternal_surname" id="maternal_surname" value="" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal">

                <!-- Tipo de Pago -->
                <div>
                    <label for="payment_type" class="block text-gray-700 font-medium">Tipo de Pago</label>
                    <select id="payment_type" name="payment_type" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                        <option value="">Selecciona el tipo de pago</option>
                        <option value="Efectivo" {{ old('payment_type') == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="Transferencia Bancaria" {{ old('payment_type') == 'Transferencia Bancaria' ? 'selected' : '' }}>Transferencia Bancaria</option>
                    </select>
                    @error('payment_type')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Servicio -->
                <div>
                    <label for="service_id" class="block text-gray-700 font-medium">Servicio</label>
                    <select id="service_id" name="service_id" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                        <option value="">Selecciona un servicio</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Monto -->
                <div>
                    <label for="amount" class="block text-gray-700 font-medium">Monto</label>
                    <input type="number" id="amount" name="amount" value="{{ old('amount') }}" placeholder="Ingresa el monto" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required step="any">
                    @error('amount')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="mt-6 text-center">
                <input type="submit" value="Registrar Pago" class="px-6 py-3 bg-principal text-white rounded-md hover:bg-terciario focus:outline-none focus:ring-2 focus:ring-principal focus:ring-opacity-50">
            </div>
        </form>
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

            // Búsqueda en el select
            $('#resident').on('change', function() {
                let residentId = $(this).val(); // Obtener el id del residente seleccionado
                
                // Buscar el residente correspondiente en el array 'allResidents'
                let selectedResident = allResidents.find(resident => resident.id == residentId);

                if (selectedResident) {
                    // Colocar el id del residente en el input correspondiente
                    $('#resident_id').val(selectedResident.id);
                    $('#name').val(selectedResident.name);
                    $('#paternal_surname').val(selectedResident.paternal_surname);
                    $('#maternal_surname').val(selectedResident.maternal_surname);
                } else {
                    // Si no se encuentra el residente, puedes manejar este caso si lo necesitas
                    console.log('Residente no encontrado');
                }
            });
        });


    </script>
    
@endsection
