<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agora I.A. | Multiplataforma Escolar</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Si usas Vite --}}
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] flex items-center justify-center min-h-screen">

    <div class="w-full max-w-6xl p-6 lg:p-12 flex flex-col-reverse lg:flex-row items-center gap-12">

        <!-- Sección de contenido -->
        <div class="flex-1">
            <h1 class="text-3xl lg:text-5xl font-bold mb-4">
                Bienvenido a <span class="text-blue-600 dark:text-yellow-400">{{ env("APP_NAME") }}</span>
            </h1>
            <p class="text-base lg:text-lg mb-6 text-gray-700 dark:text-gray-300">
                Una multiplataforma escolar inteligente que conecta a estudiantes, docentes, colegios y acudientes.
                Gestiona tareas, exámenes, foros, notas y más, todo desde un solo lugar.
            </p>

            @if (Route::has('login'))
                <div class="flex gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition">
                            Ir al Panel
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition">
                            Ingresar
                        </a>
                        {{-- <a href="{{ route('register') }}"
                           class="border border-blue-600 text-blue-600 dark:text-yellow-400 dark:border-yellow-400 px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-50 dark:hover:bg-[#1f1f1f] transition">
                            Registrarse
                        </a> --}}
                    @endauth
                </div>
            @endif
        </div>

        <!-- Imagen o Ilustración -->
        <div class="flex-1">
            <img src="{{ asset('images/Online learning-amico.svg') }}" alt="Plataforma Escolar" class="w-full max-w-md mx-auto dark:brightness-90">
        </div>

    </div>

</body>
</html>
