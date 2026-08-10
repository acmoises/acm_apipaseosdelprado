@extends('layouts.bitacoras')

@section('content')
<div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-semibold text-center text-gray-900 mb-6">Nuevo Registro</h2>

    <form action="{{ route('bitacoras.store') }}" method="POST">
        @csrf

        <div class="space-y-4">
            <!-- Nota -->
            <div>
                <label for="nota" class="block text-gray-700 font-medium">Nota</label>
                <textarea id="nota" name="nota" rows="6"
                    class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal"
                    placeholder="Escribe los detalles de tu registro..."></textarea>
                @error('nota')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Estatus -->
            <div>
                <label for="estatus" class="block text-gray-700 font-medium">Estatus</label>
                <select id="estatus" name="estatus"
                    class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal">
                    <option value="pendiente">Pendiente</option>
                    <option value="finalizado">Finalizado</option>
                </select>
                @error('estatus')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="mt-6 text-center">
            <button type="submit"
                class="px-6 py-3 bg-principal text-white rounded-md hover:bg-terciario focus:outline-none focus:ring-2 focus:ring-principal focus:ring-opacity-50">
                Guardar Bitácora
            </button>
        </div>
    </form>
</div>

{{-- CKEditor 5 --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#nota'), {
            toolbar: [
                'undo', 'redo', '|',
                'heading', '|',
                'bold', 'italic', 'underline', 'link', '|',
                'bulletedList', 'numberedList', '|',
                'alignment', 'blockQuote', 'insertTable'
            ],
        })
        .catch(error => console.error(error));
</script>
@endsection

