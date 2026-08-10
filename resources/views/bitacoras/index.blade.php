@extends('layouts.bitacoras')

@section('content')
<div class="max-w-4xl mx-auto mt-8 space-y-4">
    <!-- Cabecera: título + botón -->
    <div class="flex justify-between items-center mb-2">
        <h1 class="text-2xl font-semibold text-gray-900">Bitácora diaria</h1>
        <a href="{{ route('bitacoras.create') }}" 
           class="px-4 py-2 bg-principal text-white rounded-md hover:bg-terciario focus:outline-none focus:ring-2 focus:ring-principal focus:ring-opacity-50">
            Nueva nota
        </a>

        <a href="{{ route('bitacoras.calendar') }}" 
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            Calendario
        </a>
        
    </div>

    @if($bitacoras->count())
        @foreach($bitacoras as $bitacora)
            <div class="bg-white shadow-lg rounded-lg p-6 border border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-500 text-sm">
                        {{ $bitacora->created_at->format('d/m/Y H:i') }}
                    </span>
                    <span class="px-2 py-1 rounded text-sm font-semibold {{ $bitacora->estatus == 'finalizado' ? 'bg-green-500 text-white' : 'bg-yellow-400 text-black' }}">
                        {{ ucfirst($bitacora->estatus) }}
                    </span>
                </div>

                <div class="mb-4">
                    {!! $bitacora->nota !!}
                </div>

                <div class="text-right">
                    <a href="{{ route('bitacoras.edit', $bitacora) }}" 
                       class="px-4 py-2 bg-principal text-white rounded-md hover:bg-terciario focus:outline-none focus:ring-2 focus:ring-principal focus:ring-opacity-50">
                        Editar
                    </a>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-gray-500 text-center mt-8">No hay registros en la bitácora.</p>
    @endif
</div>
@endsection
