<!-- resources/views/errors/404.blade.php -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center">
        <!-- Imagen centrada -->
        <img src="{{ asset('images/404-nbg.png') }}" alt="404" class="mx-auto w-1/3 mb-8">

        <!-- Texto -->
        <h1 class="text-4xl font-bold mb-4">¡Oops! Página no encontrada</h1>
        <p class="text-gray-600 mb-8">La página que buscas no existe o ha sido movida.</p>

        <!-- Botón para volver atrás -->
        <a href="{{ url()->previous() }}" 
           class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded transition">
           Volver atrás
        </a>
    </div>
</body>
</html>