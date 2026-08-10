<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Residente</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
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