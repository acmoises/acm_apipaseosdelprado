<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controlde Entradas</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen items-center justify-center">
        <!-- Contenido Principal -->
        <div class="flex-1 p-8">
            @yield('content')
        </div>
    </div>
</body>
</html>