<x-layouts.app>
    <div class="flex flex-col gap-8">

        {{-- Título Principal --}}
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-neutral-800 dark:text-white">Panel del Profesor</h2>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">Accesos rápidos y vista general de tus clases.</p>
        </div>

        {{-- Accesos rápidos --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-5">
            {{-- Notas --}}
            <a href="{{ route('docente-notas') }}"
                class="flex flex-col items-center justify-center gap-2 p-5 rounded-xl bg-white dark:bg-neutral-800 shadow-sm hover:shadow-md transition-all duration-200 border border-neutral-200 dark:border-neutral-700 group">
                <svg class="w-9 h-9 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M8 4h8M9 2h6a2 2 0 0 1 2 2v2H7V4a2 2 0 0 1 2-2z" />
                    <path d="M21 10H3v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V10z" />
                </svg>
                <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Notas</span>
            </a>

            {{-- Asistencias --}}
            <a href="{{ route('docente-asistencias') }}"
                class="flex flex-col items-center justify-center gap-2 p-5 rounded-xl bg-white dark:bg-neutral-800 shadow-sm hover:shadow-md transition-all duration-200 border border-neutral-200 dark:border-neutral-700 group">
                <svg class="w-9 h-9 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Asistencias</span>
            </a>

            {{-- Actividades --}}
            <a href="{{ route('docente-actividades') }}"
                class="flex flex-col items-center justify-center gap-2 p-5 rounded-xl bg-white dark:bg-neutral-800 shadow-sm hover:shadow-md transition-all duration-200 border border-neutral-200 dark:border-neutral-700 group">
                <svg class="w-9 h-9 text-orange-500 dark:text-orange-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 4h16v16H4z" />
                    <path d="M4 9h16" />
                    <path d="M9 4v5" />
                </svg>
                <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Actividades</span>
            </a>

            {{-- Evaluaciones --}}
            <a href="{{ route('docente-evaluaciones') }}"
                class="flex flex-col items-center justify-center gap-2 p-5 rounded-xl bg-white dark:bg-neutral-800 shadow-sm hover:shadow-md transition-all duration-200 border border-neutral-200 dark:border-neutral-700 group">
                <svg class="w-9 h-9 text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 3H7a2 2 0 0 0-2 2v16l7-3 7 3V5a2 2 0 0 0-2-2z" />
                </svg>
                <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Evaluaciones</span>
            </a>

            {{-- Anuncios --}}
            <a href="{{ route('docente-anuncios') }}"
                class="flex flex-col items-center justify-center gap-2 p-5 rounded-xl bg-white dark:bg-neutral-800 shadow-sm hover:shadow-md transition-all duration-200 border border-neutral-200 dark:border-neutral-700 group">
                <svg class="w-9 h-9 text-yellow-600 dark:text-yellow-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 11l18-5v12l-18-5v-2z" />
                    <path d="M21 16V8" />
                    <path d="M3 21h18" />
                </svg>
                <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Anuncios</span>
            </a>

            {{-- Horarios --}}
            <a href="{{ route('docente-horarios') }}"
                class="flex flex-col items-center justify-center gap-2 p-5 rounded-xl bg-white dark:bg-neutral-800 shadow-sm hover:shadow-md transition-all duration-200 border border-neutral-200 dark:border-neutral-700 group">
                <svg class="w-9 h-9 text-cyan-600 dark:text-cyan-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
                <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Horarios</span>
            </a>
        </div>

        {{-- Mis grupos asignados --}}
        <div class="p-6 rounded-xl bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 shadow">
            <h3 class="text-xl font-semibold text-neutral-800 dark:text-white mb-4">Mis grupos asignados</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @if (isset($gruposProfesor) && count($gruposProfesor) > 0)
                @foreach ($gruposProfesor as $grupo)
                    <div class="flex items-center justify-between bg-neutral-50 dark:bg-neutral-800 p-4 rounded-lg border border-neutral-200 dark:border-neutral-700 hover:shadow-sm transition">
                        <div>
                            <h4 class="text-lg font-semibold text-neutral-800 dark:text-white">{{ $grupo['nombre'] }}</h4>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400"> {{ $grupo->estudiantes->count() }} estudiantes</p>
                        </div>
                        <a href="{{ route('docente-grupo',$grupo->id) }}">
                            <svg class="w-6 h-6 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 18l6-6-6-6" />
                            </svg>
                        </a>
                    </div>
                @endforeach
                @else
                    <div>
                        <h4 class="text-lg font-semibold text-red-500 dark:text-red-500">Sin grupos asignados.</h4>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-layouts.app>
