<div class="space-y-6">
    {{-- Notificaciones fijas a la derecha superior --}}
    <div class="fixed top-4 right-4 z-50 cursor-pointer">
        <livewire:notificaciones />
    </div>

    {{-- Parte Superior adaptativa --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4  rounded-xl
                bg-white dark:bg-gray-900  transition-colors">

        {{-- Migas de pan --}}
        <div class="w-full md:w-auto">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('estudiante-notas') }}">Mis Notas</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ $grupo->nombre }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>

        {{-- Selector de período y botón --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto">
            <div class="w-full sm:w-auto">
                <label for="periodo" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Seleccionar Período
                </label>
                <select id="periodo" wire:model.live="periodoSeleccionado"
                        class="w-full sm:w-auto cursor-pointer rounded-lg px-5 py-2 border-gray-300 bg-white text-black
                               dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm">
                    @foreach ($periodos as $periodo)
                        <option value="{{ $periodo->id }}">
                            {{ $periodo->nombre }} ({{ $periodo->fecha_inicio->format('d/m/Y') }} - {{ $periodo->fecha_fin->format('d/m/Y') }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- boton descargar --}}

            <div>
                <label class="invisible sm:invisible block mb-1 text-sm font-medium">&nbsp;</label>

                <button wire:click="descargarNotas"
                        wire:loading.attr="disabled"
                        class="w-full cursor-pointer sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700
                               text-white text-sm font-semibold rounded-lg shadow transition disabled:opacity-70 disabled:cursor-not-allowed">

                    {{-- Icono (heroicons) --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12v8m0 0l-4-4m4 4l4-4m-4-8v4" />
                    </svg>

                    <span wire:loading.remove wire:target="descargarNotas">Descargar Notas</span>
                    <span wire:loading wire:target="descargarNotas">Generando...</span>
                </button>
            </div>

        </div>
    </div>

    {{-- Título --}}
    <h2 class="text-3xl font-bold text-blue-700 dark:text-blue-300 px-4">📘 Notas Finales por Asignatura</h2>

    {{-- Tabla responsive --}}
    <div class="overflow-x-auto px-4">
        <div class="min-w-full rounded-xl shadow-xl ring-1 ring-blue-200 dark:ring-blue-700
                    bg-gradient-to-br from-white via-blue-50 to-blue-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">

            <table class="min-w-full divide-y divide-blue-200 dark:divide-blue-800 text-sm">
                <thead class="bg-blue-100 dark:bg-blue-900">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold text-blue-900 dark:text-blue-100 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left font-bold text-blue-900 dark:text-blue-100 uppercase tracking-wider">Asignatura</th>
                        <th class="px-4 py-3 text-center font-bold text-blue-900 dark:text-blue-100 uppercase tracking-wider">Nota Final</th>
                        <th class="px-4 py-3 text-center font-bold text-blue-900 dark:text-blue-100 uppercase tracking-wider">Observaciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-100 dark:divide-blue-800">
                    @forelse ($asignaturas as $index => $asignatura)
                        <tr class="hover:bg-blue-50 dark:hover:bg-blue-800 transition">
                            <td class="px-4 py-4 text-blue-800 dark:text-blue-200">{{ $index + 1 }}</td>
                            <td class="px-4 py-4 font-semibold text-gray-900 dark:text-white">{{ $asignatura->nombre }}</td>
                            <td class="px-4 py-4 text-center font-semibold text-gray-800 dark:text-gray-100">
                                {{ number_format($asignatura->nota_final, 2) }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($asignatura->nota_final >= $notaMinima)
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Aprobado</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">Reprobado</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-gray-600 dark:text-gray-400">
                                No hay notas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
