@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-semibold text-center text-gray-900 mb-6">Registrar Nómina</h2>

    <form action="{{ route('roster.store') }}" method="POST">
        @csrf
        @method('POST')

        <div class="space-y-4">

            <!-- Nombre -->
            <div>
                <label for="name" class="block text-gray-700 font-medium">Nombre</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Ingresa el nombre completo" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Concepto -->
            <div>
                <label for="concept" class="block text-gray-700 font-medium">Concepto</label>
                <input type="text" id="concept" name="concept" value="{{ old('concept') }}" placeholder="Ingresa el concepto del gasto" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                @error('concept')
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
@endsection