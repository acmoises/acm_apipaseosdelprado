@extends('layouts.dashboard')

@section('content')
    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold text-center text-gray-900 mb-6">Crear Residente</h2>

        <form action="{{ route('resident.store') }}" method="POST">
            @csrf
            @method('POST')

            <div class="space-y-4">

                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-gray-700 font-medium">Nombre</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Ingresa el nombre" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Apellido Paterno -->
                <div>
                    <label for="paternal_surname" class="block text-gray-700 font-medium">Apellido Paterno</label>
                    <input type="text" id="paternal_surname" name="paternal_surname" value="{{ old('paternal_surname') }}" placeholder="Ingresa el apellido paterno" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                    @error('paternal_surname')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Apellido Materno -->
                <div>
                    <label for="maternal_surname" class="block text-gray-700 font-medium">Apellido Materno</label>
                    <input type="text" id="maternal_surname" name="maternal_surname" value="{{ old('maternal_surname') }}" placeholder="Ingresa el apellido materno" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal">
                    @error('maternal_surname')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="phone_number" class="block text-gray-700 font-medium">Número de Teléfono</label>
                    <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="Ingresa el número de teléfono" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal">
                    @error('phone_number')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Dirección -->
                <div>
                    <label for="address" class="block text-gray-700 font-medium">Dirección</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="Ingresa la dirección" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal">
                    @error('address')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- ID de tarjeta -->
                <div>
                    <label for="card_id" class="block text-gray-700 font-medium">ID de Tarjeta</label>
                    <input type="text" id="card_id" name="card_id" value="{{ old('card_id') }}" placeholder="Ingresa el ID de la tarjeta" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal">
                    @error('card_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-gray-700 font-medium">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Ingresa el Email" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="mt-6 text-center">
                <input type="submit" value="Registrar Residente" class="px-6 py-3 bg-principal text-white rounded-md hover:bg-terciario focus:outline-none focus:ring-2 focus:ring-principal focus:ring-opacity-50">
            </div>
        </form>
    </div>

@endsection