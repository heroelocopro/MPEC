<div>
    {{-- Parte Superior --}}
    <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
        {{-- Migajas de pan --}}
        <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#" class="hover:underline">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item class="hover:underline">Inicio</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
    </div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        {{-- Cartas de estadísticas principales --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            {{-- Total de Colegios --}}
            <div class="p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Colegios</h3>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalColegios }}</p>
                    </div>
                    <div class="text-blue-500 dark:text-blue-400">
                        {{-- Icono: Escuela (School) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M3 11l9-7 9 7v10a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V11z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total de Estudiantes --}}
            <div class="p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Estudiantes</h3>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalEstudiantes }}</p>
                    </div>
                    <div class="text-green-500 dark:text-green-400">
                        {{-- Icono: Usuarios (Users) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M17 20h5v-2a4 4 0 00-5-4m-6 6v-2a4 4 0 00-5-4H2v6h5m5-6a4 4 0 100-8 4 4 0 000 8zM17 11a4 4 0 100-8 4 4 0 000 8z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total de Profesores --}}
            <div class="p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Profesores</h3>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalProfesores }}</p>
                    </div>
                    <div class="text-purple-500 dark:text-purple-400">
                        {{-- Icono: Pizarra o maestro --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M4 6h16M4 10h16M4 14h10m-5 6h5"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contenido inferior --}}
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Distribución de Estudiantes por Colegio</h3>

            {{-- Aquí podrías integrar un gráfico Livewire o Chart.js --}}
            {{-- @livewire('administrador.dashboard.graficos') --}}
            <livewire:admin.administrador-dashboard-graficos />
        </div>
    </div>
</div>
