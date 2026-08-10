<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <style>
        /* Estilo para los submenús */
        .submenu {
            display: none; /* Los submenús estarán ocultos inicialmente */
        }

        .submenu.show {
            display: block; /* Mostrar el submenú cuando tenga la clase 'show' */
        }

        .menu-item:hover .submenu {
            display: block; /* Mostrar el submenú cuando el mouse esté sobre el ítem */
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Menú Lateral (Fijo) -->
        <div class="w-64 bg-principal text-white p-4 fixed top-0 left-0 h-full">
            <a href="{{ route('dashboard') }}">
                <h2 class="text-2xl font-semibold mb-6 text-center">Nueva Administracion</h2>
            </a>
            <ul>

                <li class="mb-4 menu-item">
                    <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-terciario rounded-md">Perfil</a>
                    <!-- Submenu de Perfil -->
                    <ul class="submenu ml-4 mt-2">
                        <li>
                            <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-terciario rounded-md">Cerrar sesion</a>
                        </li>
                        <li>
                            <!-- <a href="" class="block px-4 py-2 hover:bg-terciario rounded-md">Ver Historial</a> -->
                        </li>
                    </ul>
                </li>

                <li class="mb-4 menu-item">
                    <p class="block px-4 py-2 hover:bg-secundario rounded-md">Residentes</p>
                    <!-- Submenu de Residentes -->
                    <ul class="submenu ml-4 mt-2">
                        <li>
                            <a href="{{route('resident.index')}}" class="block px-4 py-2 hover:bg-secundario rounded-md">Administrar</a>
                        </li>
                        <li>
                            <a href="{{route('resident.create')}}" class="block px-4 py-2 hover:bg-secundario rounded-md">Nuevo Residente</a>
                        </li>
                        @if (Auth::user()->id === 1)
                            <li>
                                <a href="{{route('resident.showImport')}}" class="block px-4 py-2 hover:bg-secundario rounded-md">Importar Residentes</a>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="mb-4 menu-item">
                    <p class="block px-4 py-2 hover:bg-secundario rounded-md">Pagos</p>
                    <!-- Submenu de Pagos -->
                    <ul class="submenu ml-4 mt-2">
                        <li>
                            <a href="{{ route('payment.index') }}" class="block px-4 py-2 hover:bg-terciario rounded-md">Ver Historial</a>
                        </li>
                        <li>
                            <a href="{{ route('payment.create') }}" class="block px-4 py-2 hover:bg-terciario rounded-md">Registrar Pago</a>
                        </li>
                        <li>
                            <a href="{{ route('payment.cancelledshow') }}" class="block px-4 py-2 hover:bg-terciario rounded-md">Pagos Cancelados</a>
                        </li>
                    </ul>
                </li>

                <li class="mb-4 menu-item">
                    <p class="block px-4 py-2 hover:bg-secundario rounded-md">Gastos</p>
                    <!-- Submenu de Gastos -->
                    <ul class="submenu ml-4 mt-2">
                        <li>
                            <a href="{{ route('spent.index') }}" class="block px-4 py-2 hover:bg-terciario rounded-md">Ver Gastos de Mes</a>
                        </li>
                        <li>
                            <a href="{{ route('spent.create') }}" class="block px-4 py-2 hover:bg-terciario rounded-md">Registrar Gastos</a>
                        </li>
                    </ul>
                </li>

                <li class="mb-4 menu-item">
                    <p class="block px-4 py-2 hover:bg-secundario rounded-md">Nomina</p>
                    <!-- Submenu de Nomina -->
                    <ul class="submenu ml-4 mt-2">
                        <li>
                            <a href="{{ route('roster.index') }}" class="block px-4 py-2 hover:bg-terciario rounded-md">Ver Nomina del Mes</a>
                        </li>
                        <li>
                            <a href="{{ route('roster.create') }}" class="block px-4 py-2 hover:bg-terciario rounded-md">Registrar Pago</a>
                        </li>
                    </ul>
                </li>

                <li class="mb-4 menu-item">
                    <p class="block px-4 py-2 hover:bg-secundario rounded-md">Boletos</p>
                    <!-- Submenu de Boletos -->
                    <ul class="submenu ml-4 mt-2">
                        <li>
                            <a href="{{ route('boletos.index') }}" class="block px-4 py-2 hover:bg-terciario rounded-md">Imprimir Boletos</a>
                        </li>
                    </ul>
                </li>

                <li class="mb-4 menu-item">
                    <p class="block px-4 py-2 hover:bg-secundario rounded-md">Bitacora</p>
                    <!-- Submenu de Bitacora -->
                    <ul class="submenu ml-4 mt-2">
                        <li>
                            <a href="{{ route('bitacoras.create') }}" class="block px-4 py-2 hover:bg-terciario rounded-md">Nuevo Registro</a>
                        </li>

                        <li>
                            <a href="{{ route('bitacoras.index') }}" class="block px-4 py-2 hover:bg-terciario rounded-md">Ver Historial</a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>

        <!-- Contenido Principal -->
        <div class="flex-1 p-8 ml-64">
            @include('layouts._partials.link_pdf')
            @include('layouts._partials.messages')
            @yield('content')
        </div>
    </div>

    <script>
        // Manejo de eventos para hacer los submenús desplegables
        $(document).ready(function() {
            $(".menu-item > p, .menu-item > a").click(function(e) {
                // Prevenir que los enlaces sean seguidos
                e.preventDefault();

                // Toggle el submenú correspondiente
                $(this).next(".submenu").toggleClass("show");
            });
        });
    </script>
</body>
</html>
