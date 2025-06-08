<div class="space-y-6">

    {{-- Encabezado superior --}}
    <div class="flex items-center justify-between">
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">
                    <a href="{{ route('docente-inicio') }}"
                        class="text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                    </a>
                </flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('docente-asistencias') }}">Notas - Periodo</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
    </div>

    {{-- Fila de selección de Periodo y Grupo --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Columna de Períodos --}}
        <div class="bg-white dark:bg-gray-900 shadow rounded-xl p-3">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-3 text-center">
                Selecciona un Periodo
            </h2>
            @if ($periodos->isEmpty())
                <p class="text-center text-gray-600 dark:text-gray-300">No hay períodos registrados.</p>
            @else
                <div class="space-y-3">
                    @foreach ($periodos as $periodo)
                        <div wire:click="seleccionarPeriodo({{ $periodo->id }})"
                            class="cursor-pointer rounded-lg border p-3 shadow-sm transition hover:shadow focus:ring
                                {{ $periodoSeleccionado === $periodo->id
                                    ? 'bg-blue-100 dark:bg-blue-800 border-blue-500'
                                    : 'bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-700' }}">
                            <div class="flex justify-between items-center mb-1">
                                <h3 class="text-base font-semibold text-blue-700 dark:text-blue-300 truncate">
                                    {{ $periodo->nombre }}
                                </h3>
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full
                                    {{ $periodo->es_activo
                                        ? 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200'
                                        : 'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-200' }}">
                                    {{ $periodo->es_activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Columna de Grupos --}}
        <div class="bg-white dark:bg-gray-900 shadow rounded-xl p-3">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-3 text-center">
                Selecciona un Grupo
            </h2>
            @if (empty($grupos))
                <p class="text-center text-gray-600 dark:text-gray-300">No hay grupos registrados.</p>
            @else
                <div class="space-y-3">
                    @foreach ($grupos as $grupo)
                        <div wire:click="seleccionarGrupo({{ $grupo->id }})"
                            class="cursor-pointer rounded-lg border p-3 shadow-sm transition hover:shadow focus:ring
                                {{ $grupoSeleccionado === $grupo->id
                                    ? 'bg-blue-100 dark:bg-blue-800 border-blue-500'
                                    : 'bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-700' }}">
                            <div class="flex justify-between items-center">
                                <h3 class="text-base font-semibold text-blue-700 dark:text-blue-300 truncate">
                                    {{ $grupo->nombre }}
                                </h3>
                                <span class="text-sm text-gray-600 dark:text-gray-300">
                                    {{ $grupo->grado->nombre }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    {{-- Notas Finales por Asignatura --}}
    @if (!empty($notasPorEstudiante))
        <div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-4 md:p-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                Planilla de Notas Finales por Asignatura
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Estudiante</th>
                            @foreach ($asignaturas as $asignaturaId => $asignaturaNombre)
                                <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $asignaturaNombre }}
                                </th>
                            @endforeach
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Promedio</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($estudiantes as $estudianteId => $nombreEstudiante)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white">
                                    {{ $nombreEstudiante }}
                                </td>
                                @foreach ($asignaturas as $asignaturaId => $asignaturaNombre)
                                    <td class="px-4 py-2 text-center">
                                        <input
                                            type="number"
                                            step="0.1"
                                            min="0"
                                            max="5"
                                            wire:model.defer="notasPorEstudiante.{{ $estudianteId }}-{{ $asignaturaId }}"
                                            wire:blur="actualizarNota({{ $estudianteId }}, {{ $asignaturaId }})"
                                            class="w-16 bg-white text-black text-center rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring focus:ring-blue-300 focus:border-blue-500"
                                        />
                                    </td>
                                @endforeach
                                <td>
                                    {{ $promediosEstudiantes[$estudianteId] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    @endif


    {{-- JavaScript --}}
    @push('js')
    <script>
        Livewire.on('alerta', (data) => {
            data = data[0];
            Swal.fire({
                title: data['title'],
                text: data['text'],
                icon: data['icon'],
                toast: data['toast'],
                position: data['position'],
                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            });
        });
    </script>
    @endpush
</div>
