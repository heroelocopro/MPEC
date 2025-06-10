<x-layouts.app :title="__('Inicio | Estudiante')">
        {{-- Notificaciones fijas a la derecha superior --}}
        <div class="fixed top-4 right-4 z-50 cursor-pointer">
            <livewire:notificaciones />
        </div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- Encabezado con saludo --}}
        <h1 class="text-xl font-bold text-gray-800 dark:text-white mb-2">
            ¡Bienvenido, {{ $estudiante->nombre_completo }}! 🎓
        </h1>



        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            {{-- Funciones del módulo --}}
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 flex flex-col justify-center bg-white dark:bg-neutral-900">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">¿Qué puedes hacer aquí?</h2>
                <ul class="text-sm text-gray-600 dark:text-gray-300 list-disc pl-5 space-y-1">
                    <li>Visualizar y resolver actividades o tareas asignadas por tus docentes.</li>
                    <li>Presentar exámenes en línea.</li>
                    <li>Participar en foros académicos y de discusión.</li>
                    <li>Consultar anuncios importantes del colegio o tus docentes.</li>
                    <li>Revisar tu horario de clases.</li>
                    <li>Consultar tus asignaturas y docentes asignados.</li>
                </ul>
            </div>

            {{-- Imagen del módulo --}}
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <img
                    class="w-full h-full object-cover"
                    src="{{ asset('modulos/modulo estudiante.png') }}"
                    alt="Imagen del módulo estudiante">
            </div>

            {{-- Secciones disponibles como accesos clicables --}}
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Accesos disponibles</h2>
                <div class="grid grid-cols-2 gap-2 text-sm text-gray-700 dark:text-gray-200">
                    <a href="{{ route('estudiante-actividades') }}" class="bg-gray-100 dark:bg-neutral-800 rounded px-3 py-2 flex items-center gap-2 hover:bg-blue-100 dark:hover:bg-blue-700 transition">
                        📝 <span>Mis Actividades</span>
                    </a>
                    <a href="{{ route('estudiante-examenes') }}" class="bg-gray-100 dark:bg-neutral-800 rounded px-3 py-2 flex items-center gap-2 hover:bg-blue-100 dark:hover:bg-blue-700 transition">
                        🧪 <span>Exámenes</span>
                    </a>
                    <a href="#" class="bg-gray-100 dark:bg-neutral-800 rounded px-3 py-2 flex items-center gap-2 hover:bg-blue-100 dark:hover:bg-blue-700 transition">
                        💬 <span>Foros</span>
                    </a>
                    <a href="{{ route('estudiante-anuncios') }}" class="bg-gray-100 dark:bg-neutral-800 rounded px-3 py-2 flex items-center gap-2 hover:bg-blue-100 dark:hover:bg-blue-700 transition">
                        📢 <span>Anuncios</span>
                    </a>
                    <a href="{{ route('estudiante-horarios') }}" class="bg-gray-100 dark:bg-neutral-800 rounded px-3 py-2 flex items-center gap-2 hover:bg-blue-100 dark:hover:bg-blue-700 transition">
                        📆 <span>Mi Horario</span>
                    </a>
                    <a href="{{ route('estudiante-asignaturas') }}" class="bg-gray-100 dark:bg-neutral-800 rounded px-3 py-2 flex items-center gap-2 hover:bg-blue-100 dark:hover:bg-blue-700 transition">
                        📚 <span>Asignaturas</span>
                    </a>
                    <a href="{{ route('estudiante-docentes') }}" class="bg-gray-100 dark:bg-neutral-800 rounded px-3 py-2 flex items-center gap-2 hover:bg-blue-100 dark:hover:bg-blue-700 transition">
                        👨‍🏫 <span>Docentes</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Anuncios recientes --}}
        <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 text-center">📢 Anuncios importantes</h2>

            @if ($anuncios->isEmpty())
                <div class="text-gray-600 dark:text-gray-400 text-sm text-center">
                    Aquí aparecerán los anuncios recientes publicados por tus docentes o el colegio. Mantente atento para no perder ninguna novedad.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($anuncios->sortByDesc('created_at') as $anuncio)
                        <div class="bg-gray-50 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-lg p-4 shadow-sm flex flex-col justify-between h-full">
                            <div>
                                @if($anuncio->imagen)
                                    <img src="{{ asset('storage/'.$anuncio->imagen) }}" alt="Imagen del anuncio"
                                         class="w-full h-32 object-cover rounded-md mb-3" />
                                @endif

                                <h3 class="text-blue-700 dark:text-blue-300 font-semibold text-sm">{{ $anuncio->titulo }}</h3>

                                <div x-data="{ open: false }">
                                    <p class="text-gray-700 dark:text-gray-300 text-sm mt-1 line-clamp-3" x-show="!open">
                                        {{ Str::limit($anuncio->contenido, 120) }}
                                    </p>
                                    <p class="text-gray-700 dark:text-gray-300 text-sm mt-1" x-show="open">
                                        {{ $anuncio->contenido }}
                                    </p>
                                    <button @click="open = !open" class="text-xs text-blue-600 hover:underline mt-2">
                                        <span x-show="!open">Leer más</span>
                                        <span x-show="open">Mostrar menos</span>
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-3 text-right">
                                {{ $anuncio->created_at->format('d M Y') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-layouts.app>
