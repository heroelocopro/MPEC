<x-layouts.app :title="__('Inicio | Docente')">
    <div class="flex flex-col gap-6">

        {{-- Navegación rápida --}}
        <div>
            <h2 class="text-2xl font-bold text-neutral-800 dark:text-white mb-4">Panel Principal</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Notas --}}
                <a href="{{ route('docente-notas') }}"
                    class="flex flex-col items-center justify-center p-4 bg-white dark:bg-neutral-800 rounded-xl shadow hover:shadow-lg transition border border-neutral-200 dark:border-neutral-700 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2 text-indigo-600 dark:text-indigo-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 4h8M9 2h6a2 2 0 0 1 2 2v2H7V4a2 2 0 0 1 2-2z" />
                        <path d="M21 10H3v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V10z" />
                    </svg>
                    <span class="font-semibold text-sm text-neutral-700 dark:text-neutral-200">Notas</span>
                </a>

                {{-- Asistencias --}}
                <a href="{{ route('docente-asistencias') }}"
                    class="flex flex-col items-center justify-center p-4 bg-white dark:bg-neutral-800 rounded-xl shadow hover:shadow-lg transition border border-neutral-200 dark:border-neutral-700 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <span class="font-semibold text-sm text-neutral-700 dark:text-neutral-200">Asistencias</span>
                </a>

                {{-- Actividades --}}
                <a href="{{ route('docente-actividades') }}"
                    class="flex flex-col items-center justify-center p-4 bg-white dark:bg-neutral-800 rounded-xl shadow hover:shadow-lg transition border border-neutral-200 dark:border-neutral-700 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2 text-orange-500 dark:text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16v16H4z" />
                        <path d="M4 9h16" />
                        <path d="M9 4v5" />
                    </svg>
                    <span class="font-semibold text-sm text-neutral-700 dark:text-neutral-200">Actividades</span>
                </a>

                {{-- Evaluaciones --}}
                <a href="{{ route('docente-evaluaciones') }}"
                    class="flex flex-col items-center justify-center p-4 bg-white dark:bg-neutral-800 rounded-xl shadow hover:shadow-lg transition border border-neutral-200 dark:border-neutral-700 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2 text-red-500 dark:text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3H7a2 2 0 0 0-2 2v16l7-3 7 3V5a2 2 0 0 0-2-2z" />
                    </svg>
                    <span class="font-semibold text-sm text-neutral-700 dark:text-neutral-200">Evaluaciones</span>
                </a>

                {{-- Anuncios --}}
                <a href="{{ route('docente-anuncios') }}"
                    class="flex flex-col items-center justify-center p-4 bg-white dark:bg-neutral-800 rounded-xl shadow hover:shadow-lg transition border border-neutral-200 dark:border-neutral-700 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2 text-yellow-500 dark:text-yellow-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 11l18-5v12l-18-5v-2z" />
                        <path d="M21 16V8" />
                        <path d="M3 21h18" />
                    </svg>
                    <span class="font-semibold text-sm text-neutral-700 dark:text-neutral-200">Anuncios</span>
                </a>
                {{-- Horarios --}}
                <a href="{{ route('docente-horarios') }}"
                class="flex flex-col items-center justify-center p-4 bg-white dark:bg-neutral-800 rounded-xl shadow hover:shadow-lg transition border border-neutral-200 dark:border-neutral-700 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2 text-cyan-600 dark:text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
                <span class="font-semibold text-sm text-neutral-700 dark:text-neutral-200">Horarios</span>
                </a>
            </div>
        </div>

        {{-- Resumen del día --}}
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h2 class="text-xl font-bold text-neutral-800 dark:text-white mb-4">Resumen del día</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300">
                    <div class="text-sm">Actividades pendientes</div>
                    <div class="text-2xl font-bold">4</div>
                </div>

                <div class="p-4 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-800 dark:text-green-300">
                    <div class="text-sm">Evaluaciones próximas</div>
                    <div class="text-2xl font-bold">2</div>
                </div>

                <div class="p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-300">
                    <div class="text-sm">Notas sin subir</div>
                    <div class="text-2xl font-bold">6</div>
                </div>

                <div class="p-4 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-300">
                    <div class="text-sm">Asistencias faltantes</div>
                    <div class="text-2xl font-bold">1</div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
