@extends('layouts.dashboard')

@section('content')
    <!-- Título de bienvenida -->
    <div class="max-w-6xl mx-auto mt-2 p-6">
        <h2 class="text-2xl font-semibold text-center text-gray-900 mb-2">
            Bienvenido, {{ Auth::user()->name .' ' . Auth::user()->lastname }}
        </h2>
    </div>

    <!-- Formulario para seleccionar el mes y el año -->
    <div class="max-w-6xl mx-auto mt-4 p-6 bg-white shadow-lg rounded-lg">
        <form action="{{ route('dashboard') }}" method="GET" class="flex justify-between">
            <div class="flex items-center">
                <label for="month" class="mr-2">Mes:</label>
                <select id="month" name="month" class="p-2 border rounded">
                    @foreach(range(1, 12) as $month)
                        <option value="{{ $month }}" {{ $month == request()->get('month', now()->month) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->locale('es')->month($month)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center">
                <label for="year" class="mr-2">Año:</label>
                <select id="year" name="year" class="p-2 border rounded">
                    @foreach(range(now()->year - 5, now()->year) as $year)
                        <option value="{{ $year }}" {{ $year == request()->get('year', now()->year) ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filtrar</button>
        </form>
    </div>

    <!-- Mostrar los resultados -->
    <div class="max-w-6xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="flex flex-col justify-center items-center bg-green-100 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-center text-gray-900 mb-4">Total Recaudado</h3>
            <p class="text-2xl text-green-600 font-bold">
                ${{ number_format($totalRecaudado, 2) }}
            </p>
        </div>

        <div class="flex flex-col justify-center items-center bg-blue-100 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-center text-gray-900 mb-4">Residentes que Pagaron</h3>
            <p class="text-2xl text-green-600 font-bold">
                {{ $residentesPagados }} residentes
            </p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="flex flex-col justify-center items-center bg-green-100 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-center text-gray-900 mb-4">Total Gastos</h3>
            <p class="text-2xl text-orange-600 font-bold">
                ${{ number_format($totalGastos, 2) }}
            </p>
        </div>

        <div class="flex flex-col justify-center items-center bg-blue-100 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-center text-gray-900 mb-4">Total Nómina</h3>
            <p class="text-2xl text-orange-600 font-bold">
                ${{ number_format($totalNomina, 2) }}
            </p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="flex flex-col justify-center items-center bg-green-100 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-center text-gray-900 mb-4">Balance Utilidad</h3>
            <p class="text-2xl text-blue-600 font-bold">
                ${{ number_format($totalRecaudado - $totalGastos - $totalNomina, 2) }}
            </p>
        </div>
    </div>
@endsection
