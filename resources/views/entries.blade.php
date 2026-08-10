@extends('layouts.entries')

@section('content')
    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold text-center text-gray-900 mb-6">Control de Entradas</h2>

        <!-- Formulario de entrada con campo para leer la tarjeta -->
        <div class="flex justify-center">
            <input type="text" id="card-id" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-principal focus:border-principal" placeholder="Escanea tu tarjeta ID aquí..." autofocus>
        </div>

        <div id="resident-info" class="mt-4 p-4 bg-blue-100 text-blue-700 rounded-md hidden">
            <h3 style="font-size: 2.25rem;" class="font-semibold text-xl">Información del Residente</h3>
            <p style="font-size: 2.25rem;" ><strong>Nombre:</strong> <span id="resident-name"></span></p>
            <p style="font-size: 2.25rem;" class="text-xl"><strong>Dirección:</strong> <span id="resident-address"></span></p>
        </div>

        <!-- Div para mostrar el mensaje -->
        <div id="message" class="hidden text-6xl font-bold p-6 mb-4 rounded-md text-center">
            <!-- El mensaje se insertará aquí -->
        </div>


        
    </div>

    <script>
        // JavaScript para capturar el código de la tarjeta
        document.getElementById('card-id').addEventListener('keydown', function (e) {
            if(e.key === 'Enter'){
                let cardId = e.target.value.trim();
                if (cardId.length > 0) {
                    
                    // Realizar la consulta a la base de datos para obtener los datos del residente
                    fetch(`/residentsentries/${cardId}`)
                        .then(response => response.json())
                        .then(data => {
                            const messageDiv = document.getElementById('message');
                            if (data) {
                                // Mostrar la información del residente
                                //document.getElementById('resident-name').textContent = data.name;
                                //document.getElementById('resident-address').textContent = data.address;
                                //document.getElementById('resident-info').classList.remove('hidden');

                                // Agregar un mensaje de éxito
                                messageDiv.classList.remove('hidden');
                                messageDiv.textContent = data.message;
                                messageDiv.style.backgroundColor = data.color; // Verde
                                messageDiv.style.color = data.styleColor;

                                // Establecer el tamaño de la fuente del mensaje directamente desde JavaScript
                                messageDiv.style.fontSize = '5rem';  // Aumenta el tamaño del texto
                                 
                                // Limpiar el campo de entrada
                                document.getElementById('card-id').value = '';

                                // Recargar la página después de 15 segundos
                                setTimeout(function() {
                                    location.reload(); // Recarga la página
                                }, 15000); // 15000 milisegundos = 15 segundos
                                
                            } else {
                                alert('Residente no encontrado.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }

            }
        });
    </script>
@endsection
