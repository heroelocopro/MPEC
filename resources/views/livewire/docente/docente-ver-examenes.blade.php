<div>
    <div class="flex items-center justify-between mb-6">
        {{-- Migajas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">
                    <a href="{{ route('docente-inicio') }}" class="text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                    </a>
                </flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('docente-evaluaciones') }}">Examenes</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="">Ver</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
        {{-- espacio para boton --}}
    </div>

    {{-- parte Principal --}}


    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Select de Asignaturas --}}
            <div>
                <label for="asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Selecciona una asignatura
                </label>
                <select wire:model.live="asignatura_id" id="asignatura"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100">
                    <option value="">-- Elegir asignatura --</option>
                    @foreach($asignaturas as $asignatura)
                        <option value="{{ $asignatura->id }}">{{ $asignatura->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Select de Grupos --}}
            <div>
                <label for="grupo" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Selecciona un grupo
                </label>
                <select wire:model.live="grupo_id" id="grupo"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100"
                    @if(!$grupos || count($grupos) == 0) disabled @endif>
                    <option value="">-- Elegir grupo --</option>
                    @foreach($grupos as $grupo)
                        <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Lista de exámenes por estudiante --}}
        <div class="mt-8">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Exámenes de los estudiantes</h2>

            @if($examenes && count($examenes) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($examenes as $examen)
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow">
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $examen->titulo }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $examen->asignatura->nombre }} - Grupo: {{ $examen->grupo->nombre }}
                            </p>
                            <br>
                            <a wire:click="mostrarExamen({{ $examen->id }})"
                                class="inline-block px-4 cursor-pointer py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-md transition-colors">
                                 Ver Examen
                             </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-300">No hay exámenes disponibles para esta selección.</p>
            @endif
        </div>
    </div>

    {{-- modal examen --}}
@if (isset($examen))
    <flux:modal wire:model.live="modal" name="ver-examen" class="max-w-xl lg:max-w-3xl">
        <div class="p-6 space-y-6 bg-white dark:bg-gray-900 rounded-lg">
            {{-- Encabezado --}}
            <div>
                <flux:heading size="lg" class="text-indigo-700 dark:text-indigo-300 font-semibold">
                    Detalle del Examen
                </flux:heading>
                <flux:text class="mt-1 text-gray-600 dark:text-gray-400">
                    Información completa del examen seleccionado.
                </flux:text>
            </div>

            {{-- Contenido del examen --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Título</span>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $examen->titulo }}</p>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Asignatura</span>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $examen->asignatura->nombre }}</p>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Grupo</span>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $examen->grupo->nombre }}</p>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Puntaje Total</span>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $examen->puntaje_total }}</p>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Fecha de Vencimiento</span>
                    <p class="text-gray-900 dark:text-white font-medium">
                        {{ \Carbon\Carbon::parse($examen->fecha_vencimiento)->format('d/m/Y') }}
                    </p>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Tiempo Límite</span>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $examen->tiempo_limite }}</p>
                </div>
            </div>

            {{-- Botón centrado --}}
            <div class="flex justify-center">
                <a href="{{ route('docente-ver-evaluacion', $examen->id ) }}"
                class="inline-block cursor-pointer px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-md transition-colors">
                    Ver Respuestas
                </a>
            </div>
        </div>
    </flux:modal>
    @endif
</div>
