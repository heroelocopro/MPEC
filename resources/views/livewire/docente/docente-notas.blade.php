<div class="">
    {{-- Breadcrumbs and alert --}}
    <div class="flex items-center justify-between mb-6 p-6">
        <div>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <div>
                            <a href="{{ route('docente-inicio') }}" class="text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                            </svg>
                            <a href="{{ route('docente-notas') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100">Notas</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-300">{{ $colegio->nombre }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        @if (!empty($grupo_id) && $grupo_id == 'a')
        <div>
            <button wire:click="$set('modalCreacion', true)" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Crear Horario
            </button>
        </div>
        @endif
    </div>

    @php
        use Carbon\Carbon;
        $fechaFin = Carbon::parse($periodo->fecha_fin);
        $hoy = Carbon::now();
        $diasRestantes = $hoy->diffInDays($fechaFin, false);
    @endphp

    @if ($diasRestantes >= 0 && $diasRestantes <= 3)
        <div class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 dark:border-yellow-600 p-4 mb-6 mx-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700 dark:text-yellow-200">
                        <span class="font-semibold">¡Atención!</span> El periodo académico finalizará el <strong>{{ $fechaFin->format('d/m/Y') }}</strong>. No se podrán modificar notas una vez finalizado.
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Main content --}}
    <div class="flex flex-col md:flex-row gap-6 px-6 pb-6">
        <!-- Sidebar -->
        <div class="w-full md:w-64 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 h-fit md:sticky md:top-6">
            <div class="text-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Periodo Actual</h2>
                <p class="text-red-500 dark:text-red-400 font-medium">{{ $periodo->periodoActual($colegio->id)->nombre }}</p>
            </div>

            <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Asignaturas
            </h3>

            <ul class="space-y-2">
                @if(isset($asignaturas) && count($asignaturas) > 0)
                    @foreach ($asignaturas as $a)
                    <li>
                        <a wire:click="cambiarAsignatura('{{ $a->asignatura->id }}')" class="flex items-center p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg cursor-pointer transition-colors duration-150">
                            <svg class="h-5 w-5 text-red-500 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="truncate">{{ $a->asignatura->nombre }} {{ $a->asignatura->codigo }}</span>
                        </a>
                    </li>
                    @endforeach
                @endif
            </ul>
        </div>

        <!-- Main content -->
        @if ($asignatura != null)
        <div class="flex-1">
            <!-- Subject header -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $asignatura->nombre }}</h1>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">{{ $asignatura->descripcion }}</p>
                    </div>

                    <div class="relative w-full md:w-64">
                        <select wire:model.live="grupo_id" class="block w-full text-black px-4 py-2 pr-8 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none dark:text-gray-100">
                            @if (isset($grupos) && count($grupos) > 0)
                                <option value="" class="text-black bg-white dark:bg-gray-700 dark:text-gray-100">Selecciona un grupo</option>
                                @foreach ($grupos as $g)
                                    <option value="{{ $g->id }}" class="dark:bg-gray-700 bg-white text-black dark:text-gray-100">{{ $g->nombre }}</option>
                                @endforeach
                            @else
                                <option value="" class="dark:bg-gray-700 dark:text-gray-100">No hay grupos</option>
                            @endif
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grades table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estudiante</th>

                                @if (!empty($actividades))
                                    @foreach ($actividades as $actividad)
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ $actividad->titulo }}</th>
                                    @endforeach
                                @endif

                                @if (!empty($examenes))
                                    @foreach ($examenes as $examen)
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ $examen->titulo }}</th>
                                    @endforeach
                                @endif

                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Definitiva</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if (isset($grupo) && count($grupo) > 0)
                                @foreach ($grupo as $index => $g)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700" wire:key="estudiante-{{ $g->estudiante->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $g->estudiante->nombre_completo }}</td>

                                    @foreach ($actividades as $actividad)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" min="0" max="5" step="0.01"
                                            wire:model.live.debounce.100ms="notas.actividad.{{ $g->estudiante->id }}.{{ $actividad->id }}"
                                            wire:key="nota-actividad-{{ $g->estudiante->id }}-{{ $actividad->id }}"
                                            class="w-20 text-black px-2 py-1 rounded-md border border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 text-center bg-white dark:bg-gray-700 dark:text-gray-100" />
                                    </td>
                                    @endforeach

                                    @foreach ($examenes as $examen)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" min="0" max="5" step="0.01"
                                            wire:model.live.debounce.100ms="notas.examen.{{ $g->estudiante->id }}.{{ $examen->id }}"
                                            wire:key="nota-examen-{{ $g->estudiante->id }}-{{ $examen->id }}"
                                            class="w-20 text-black px-2 py-1 rounded-md border border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 text-center bg-white dark:bg-gray-700 dark:text-gray-100" />
                                    </td>
                                    @endforeach

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                        @php
                                            $notasActividad = $notas['actividad'][$g->estudiante->id] ?? [];
                                            $notasExamen = $notas['examen'][$g->estudiante->id] ?? [];

                                            $todasLasNotas = collect(array_merge($notasActividad, $notasExamen))
                                                ->map(function ($nota) {
                                                    return is_numeric($nota) ? floatval($nota) : 0;
                                                });

                                            $promedio = $todasLasNotas->avg();

                                            if ($promedio < 3.5) {
                                                $color = 'text-red-600 dark:text-red-400';
                                            } elseif ($promedio > 3.5) {
                                                $color = 'text-green-600 dark:text-green-400';
                                            } else {
                                                $color = 'text-yellow-500 dark:text-yellow-400';
                                            }
                                        @endphp

                                        <span class="{{ $color }}">
                                            {{ number_format($promedio, 1) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end">
                    <button wire:click="guardarNotas" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Guardar Notas
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>


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
