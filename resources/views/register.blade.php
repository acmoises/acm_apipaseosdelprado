@extends('layouts.register')

@section('content')
    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold text-center text-gray-900 mb-6">Crear Usuario</h2>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="space-y-4">

                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-gray-700 font-medium">Nombre</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Ingresa tu nombre" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Apellidos -->
                <div>
                    <label for="lastname" class="block text-gray-700 font-medium">Apellidos</label>
                    <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" placeholder="Ingresa tus apellidos" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                    @error('lastname')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Correo Electrónico -->
                <div>
                    <label for="email" class="block text-gray-700 font-medium">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Ingresa tu correo electrónico" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-gray-700 font-medium">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label for="password_confirmation" class="block text-gray-700 font-medium">Confirmar Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirma tu contraseña" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" required>
                    @error('password_confirmation')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="mt-6 text-center">
                <input type="submit" value="Guardar" class="px-6 py-3 bg-principal text-white rounded-md hover:bg-terciario focus:outline-none focus:ring-2 focus:ring-principal focus:ring-opacity-50">
            </div>
        </form>
    </div>

@endsection