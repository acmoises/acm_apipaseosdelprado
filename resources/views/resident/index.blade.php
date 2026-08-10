@extends('layouts.dashboard')

@section('content')
    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold text-center text-gray-900 mb-6">Residentes</h2>

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

        <!-- Tabla de residentes -->
        <div class="overflow-x-auto">
            <table id="resident-table" class="min-w-full bg-white border border-gray-200 rounded-md shadow-md">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-2 text-left whitespace-nowrap">ID</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Nombre</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Apellido Paterno</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Apellido Materno</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Teléfono</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Dirección</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">ID de Tarjeta</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Email</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($residents as $resident)
                        <tr class="border-t border-gray-200">
                            <td class="px-4 py-2 whitespace-nowrap">{{ $resident->id }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $resident->name }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $resident->paternal_surname }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $resident->maternal_surname }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $resident->phone_number }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $resident->address }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $resident->card_id }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $resident->email }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                <a href="{{ route('resident.edit', $resident->id) }}" class="px-4 py-2 whitespace-nowrap bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 ">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @endforeach
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

            // Función para cargar los resultados en la tabla
            function loadTableData(residents) {
                let tableBody = $('#resident-table tbody');
                tableBody.empty(); // Limpiar la tabla antes de agregar nuevos datos

                if (residents.length === 0) {
                    tableBody.append('<tr><td colspan="7" class="text-center text-gray-500">No se encontraron residentes.</td></tr>');
                } else {
                    residents.forEach(function(resident) {
                        tableBody.append(`
                            <tr class="border-t border-gray-200">
                                <td class="px-4 py-2 whitespace-nowrap">${resident.id}</td>
                                <td class="px-4 py-2 whitespace-nowrap">${resident.name}</td>
                                <td class="px-4 py-2 whitespace-nowrap">${resident.paternal_surname}</td>
                                <td class="px-4 py-2 whitespace-nowrap">${resident.maternal_surname}</td>
                                <td class="px-4 py-2 whitespace-nowrap">${resident.phone_number}</td>
                                <td class="px-4 py-2 whitespace-nowrap">${resident.address}</td>
                                <td class="px-4 py-2 whitespace-nowrap">${resident.card_id}</td>
                                <td class="px-4 py-2 whitespace-nowrap">${resident.email}</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <a href="{{ url('dashboard/resident/edit') }}/${resident.id}" class="px-4 py-2 whitespace-nowrap bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        `);
                    });
                }
            }

            // Cargar todos los residentes al iniciar
            loadTableData(allResidents);

            // Búsqueda en el select
            $('#resident').on('change', function() {
                let residentId = $(this).val(); // Obtener el id del residente seleccionado
                if (residentId) {
                    // Filtrar los residentes que coincidan con el id seleccionado
                    let filteredResidents = allResidents.filter(resident => resident.id == residentId);
                    // Actualizar la tabla con el residente filtrado
                    loadTableData(filteredResidents);
                } else {
                    // Si no se seleccionó ningún residente, mostrar todos
                    loadTableData(allResidents);
                }
            });
        });


    </script>

@endsection
