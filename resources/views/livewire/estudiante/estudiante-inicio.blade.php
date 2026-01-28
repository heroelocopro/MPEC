<div>
     {{-- Notificaciones fijas --}}
    <div class="fixed top-4 right-4 z-50">
        <livewire:notificaciones />
    </div>

    <div class="flex flex-col gap-6 p-4">
        {{-- Encabezado --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">👋 ¡Hola, {{ $estudiante->nombre_completo ?? 'test' }}!</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Aquí encontrarás todas tus herramientas académicas reunidas.</p>
            @if ($estudiante == null)
                <p class="text-sm text-red-600 dark:text-red-400 mt-1">Actualmente no cuentas con un colegio.</p>
            @endif
        </div>

        {{-- Accesos principales --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-4">
            @php
                $accesos = [
                    ['label' => 'Inicio', 'icon' => '🏠', 'route' => route('dashboard')],
                    // ['label' => 'Estudiantil', 'icon' => '🎓', 'route' => route('perfil-estudiante')],
                    ['label' => 'Mis Actividades', 'icon' => '📝', 'route' => route('estudiante-actividades')],
                    ['label' => 'Exámenes', 'icon' => '🧪', 'route' => route('estudiante-examenes')],
                    // ['label' => 'Foros', 'icon' => '💬', 'route' => '#'],
                    // ['label' => 'Informativo', 'icon' => '📰', 'route' => '#'],
                    ['label' => 'Asignaturas', 'icon' => '📚', 'route' => route('estudiante-asignaturas')],
                    ['label' => 'Anuncios', 'icon' => '📢', 'route' => route('estudiante-anuncios')],
                    ['label' => 'Docentes', 'icon' => '👨‍🏫', 'route' => route('estudiante-docentes')],
                    ['label' => 'Horario', 'icon' => '📆', 'route' => route('estudiante-horarios')],
                    ['label' => 'Mis Notas', 'icon' => '📈', 'route' => route('estudiante-notas')],
                ];
            @endphp

            @foreach ($accesos as $acceso)
                <a href="{{ $acceso['route'] }}"
                   class="bg-white  dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm hover:bg-blue-100 dark:hover:bg-blue-800 transition">
                    <div class="text-3xl mb-2">{{ $acceso['icon'] }}</div>
                    <div class="text-sm font-medium text-gray-800 dark:text-white">{{ $acceso['label'] }}</div>
                </a>
            @endforeach
        </div>

        {{-- Anuncios importantes --}}
        <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-4">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">📢 Anuncios recientes</h2>

            @if ($anuncios == null || empty($anuncios))
                <p class="text-gray-600 dark:text-gray-400 text-sm text-center">No hay anuncios por ahora. ¡Mantente atento!</p>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($anuncios->sortByDesc('created_at') as $anuncio)
                        <div class="bg-gray-50 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-lg p-4 shadow-sm flex flex-col justify-between h-full">
                            <h3 class="text-blue-700 dark:text-blue-300 font-semibold text-sm mb-2">{{ $anuncio->titulo }}</h3>
                            <p class="text-gray-700 dark:text-gray-300 text-sm line-clamp-3">{{ Str::limit($anuncio->contenido, 100) }}</p>
                            <span class="text-xs text-gray-500 dark:text-gray-400 mt-2 block text-right">{{ $anuncio->created_at->format('d M Y') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
