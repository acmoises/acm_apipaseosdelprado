@extends('layouts.dashboard')

@section('content')

    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold text-center text-gray-900 mb-6">Registrar Gasto</h2>

        <form action="{{ route('spent.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <div class="space-y-4">

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

                <!-- Evidencia -->
                <div>
                    <label for="evidence" class="block text-gray-700 font-medium">Evidencia</label>
                    <input type="file" id="evidence" name="evidence" value="{{ old('evidence') }}" placeholder="Ingresa el monto" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                    @error('evidence')
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