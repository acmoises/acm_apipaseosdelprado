@extends('layouts.dashboard')

@section('content')
    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold text-center text-gray-900 mb-6">Importar Residentes desde Excel</h2>

        <form action="{{ route('resident.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-4">

                <!-- Archivo Excel -->
                <div>
                    <label for="file" class="block text-gray-700 font-medium">Seleccionar archivo Excel</label>
                    <input type="file" name="file" id="file" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                    @error('file')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <!-- Botón de Importar -->
            <div class="mt-6 text-center">
                <button type="submit" class="px-6 py-3 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Importar
                </button>
            </div>
        </form>

        @if(session('success'))
            <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-md">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endsection
