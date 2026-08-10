@extends('layouts.dashboard')

@section('content')
    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold text-center text-gray-900 mb-6">Imprimir Boletos</h2>

        <form action="{{ route('boletos.imprimir') }}" method="POST" target="_blank">
            @csrf
            @method('POST')

            <div class="space-y-4">
                <!-- Folio inicial -->
                <div>
                    <label for="folio_inicial" class="block text-gray-700 font-medium">Folio Inicial</label>
                    <input type="number" id="folio_inicial" name="folio_inicial" value="{{ old('folio_inicial') }}" placeholder="Ingresa el Folio inicial" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required step="any">
                    @error('folio_inicial')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Folio Final -->
                <div>
                    <label for="folio_final" class="block text-gray-700 font-medium">Folio Final</label>
                    <input type="number" id="folio_final" name="folio_final" value="{{ old('folio_final') }}" placeholder="Ingresa el Folio Final" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required step="any">
                    @error('folio_final')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Fecha -->
                <div>
                    <label for="fecha" class="block text-gray-700 font-medium">Fecha</label>
                    <input type="date" id="fecha" name="fecha" value="{{ old('fecha') }}" placeholder="Ingresa la Fecha" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required step="any">
                    @error('fecha')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-gray-700 font-medium">Color</label>
                    <input type="color" id="color" name="color" value="{{ old('color', '#000000') }}"
                        class="w-full h-12 p-1 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                    @error('color')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Figura -->
                <div>
                    <label for="figura" class="block text-gray-700 font-medium">Figura</label>
                    <select name="figura" id="figura" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                        <option selected disabled>-- Selecciona una Figura --</option>
                        <option value="circulo">Círculo</option>
                        <option value="cuadrado">Cuadrado</option>
                        <option value="triangulo">Triángulo</option>
                    </select>
                    @error('figura')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="mt-6 text-center">
                <input type="submit" value="Imprimir" class="px-6 py-3 bg-principal text-white rounded-md hover:bg-terciario focus:outline-none focus:ring-2 focus:ring-principal focus:ring-opacity-50">
            </div>
        </form>
    </div>

    
@endsection