@extends('layouts.dashboard')

@section('content')

<div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-semibold text-center text-gray-900 mb-6">Historial de Nómina</h2>

    <!-- Formulario de búsqueda de pagos -->
    <div class="flex space-x-4">
        <div class="flex-1">
            <label for="date" class="block text-gray-700 font-medium">Fecha Inicial</label>
            <input type="date" id="date" name="date" value="{{ old('date') }}" placeholder="Fecha Inicial" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
            @error('date')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    
        <div class="flex-1">
            <label for="dateF" class="block text-gray-700 font-medium">Fecha Final</label>
            <input type="date" id="dateF" name="dateF" value="{{ old('dateF') }}" placeholder="Fecha Final" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
            @error('dateF')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    </div>
    
    <div class="mt-6 text-center">
        <input type="button" id="buscar" value="Buscar Pagos" class="px-6 py-3 bg-principal text-white rounded-md hover:bg-terciario focus:outline-none focus:ring-2 focus:ring-principal focus:ring-opacity-50">
    </div>

    <br><br>

    <!-- Tabla de pagos -->
    <table id="rosters-table" class="min-w-full bg-white border border-gray-200 rounded-md shadow-md">
        <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="px-4 py-2 text-left">Nombre</th>
                <th class="px-4 py-2 text-left">Cantidad</th>
                <th class="px-4 py-2 text-left">Fecha</th>
                <th class="px-4 py-2 text-left">Usuario</th>
                <th class="px-4 py-2 text-left">Recibo de Pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rosters as $roster)
                <tr class="border-t border-gray-200">
                    <td class="px-4 py-2">{{ $roster->rname }}</td>
                    <td class="px-4 py-2">{{ $roster->amount }}</td>
                    <td class="px-4 py-2">{{ $roster->created_at }}</td>
                    <td class="px-4 py-2">{{ $roster->uname ." " . $roster->lastname }}</td>
                    <td class="px-4 py-2">
                        <a href="/storage/roster/roster-{{ $roster->roster_identifier }}.pdf" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500" target="_blank">
                            Ver
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        // Evento al hacer clic en el botón "Buscar Pagos"
        $('#buscar').on('click', function() {
            let dateStart = $('#date').val(); // Obtener la fecha inicial
            let dateEnd = $('#dateF').val();  // Obtener la fecha final

            // Verificar si las fechas están vacías
            if (!dateStart || !dateEnd) {
                alert('Por favor, selecciona ambas fechas');
                return;
            }

            // Realizar la petición AJAX
            $.ajax({
                url: '{{ route("roster.search") }}', // Ruta a la que se enviará la petición
                method: 'GET',
                data: {
                    date: dateStart,
                    dateF: dateEnd
                },
                success: function(response) {
                    // Limpiar la tabla antes de cargar los nuevos datos
                    $('#rosters-table tbody').empty();

                    // Verificar si hay datos
                    if (response.rosters.length === 0) {
                        $('#rosters-table tbody').append('<tr><td colspan="5" class="text-center text-gray-500">No se encontraron pagos en este rango de fechas.</td></tr>');
                    } else {
                        // Llenar la tabla con los nuevos datos
                        response.rosters.forEach(function(roster) {
                            // Generamos el enlace al archivo PDF usando el identificador del pago
                            let pdfUrl = `/storage/roster/roster-${roster.roster_identifier}.pdf`;
                            $('#rosters-table tbody').append(`
                                <tr class="border-t border-gray-200">
                                    <td class="px-4 py-2">${roster.rname}</td>
                                    <td class="px-4 py-2">${roster.amount}</td>
                                    <td class="px-4 py-2">${roster.created_at}</td>
                                    <td class="px-4 py-2">${roster.uname} ${roster.lastname}</td>
                                    <td class="px-4 py-2">
                                        <a href="${pdfUrl}" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500" target="_blank">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            `);
                        });
                    }
                },
                error: function() {
                    alert('Hubo un error al obtener los datos');
                }
            });
        });
    });
</script>

@endsection
