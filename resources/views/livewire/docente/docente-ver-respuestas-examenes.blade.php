<div>
    <div class="space-y-6">
        {{-- Encabezado --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <flux:breadcrumbs>
                    <flux:breadcrumbs.item href="#">
                        <a href="{{ route('docente-inicio') }}" class="text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                        </a>
                    </flux:breadcrumbs.item>
                    <flux:breadcrumbs.item href="{{ route('docente-evaluaciones') }}">Exámenes</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item href="#">Ver</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
            {{-- Puedes añadir un botón aquí si deseas --}}
            <a href="{{ route('docente-ver-evaluaciones') }}"
            class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition">
                Volver
            </a>

        </div>

        {{-- Información del examen --}}
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Información del Examen</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm">
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
                    <span class="text-gray-500 dark:text-gray-400">Fecha Vencimiento</span>
                    <p class="text-gray-900 dark:text-white font-medium">
                        {{ \Carbon\Carbon::parse($examen->fecha_vencimiento)->format('d/m/Y') }}
                    </p>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Tiempo Límite</span>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $examen->tiempo_limite }}</p>
                </div>
            </div>
        </div>

        {{-- Respuestas de estudiantes --}}
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Respuestas de los Estudiantes</h2>

            @if(count($respuestas) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-sm">
                            <tr>
                                <th class="px-4 py-2 text-left">Estudiante</th>
                                <th class="px-4 py-2 text-left">Puntaje Obtenido</th>
                                <th class="px-4 py-2 text-left">Estado</th>
                                <th class="px-4 py-2 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                            @foreach($respuestas as $respuesta)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-2 text-gray-900 dark:text-white">
                                        {{ $respuesta->estudiante->nombre_completo }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-800 dark:text-gray-300">
                                        {{ $examen->puntajeObtenidoPorEstudiante($respuesta->estudiante->id) }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @if( $examen->yaRealizoExamen($respuesta->estudiante->id) )
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-100">Completado</span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-800 dark:text-yellow-100">En proceso</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <a wire:click="verDetalleRespuesta({{ $respuesta->id }})"
                                           class="text-blue-600 hover:underline dark:text-blue-400 cursor-pointer">
                                            Ver Detalle
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-300">No hay respuestas registradas aún.</p>
            @endif
        </div>
    </div>


    {{-- modal para ver las respuestas del examen --}}

    <flux:modal wire:model.live="mostrarModal" name="ver-examen" class="max-w-3xl lg:max-w-5xl">
        @if (!empty($detalleEstudiante))
        <div class="p-6 space-y-6 bg-white dark:bg-gray-900 rounded-lg">

            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Respuestas de {{ $detalleEstudiante->nombre_completo }}
            </h2>

            <div class="space-y-4 text-left">
                @foreach ($preguntasRespuestas as $item)
                    <div class="border border-gray-300 dark:border-gray-700 p-4 rounded-lg bg-white dark:bg-gray-800 shadow-sm">
                        <div class="mb-2">
                            <span class="block text-sm font-medium text-gray-600 dark:text-gray-400">Pregunta:</span>
                            <p class="text-gray-800 dark:text-white whitespace-pre-wrap">{{ $item['pregunta']->pregunta }}</p>
                        </div>

                        <div class="mb-2">
                            <span class="block text-sm font-medium text-gray-600 dark:text-gray-400">Respuesta del estudiante:</span>
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                                {{ $item['respuesta']->respuesta ?? 'Sin respuesta' }}
                            </p>
                        </div>

                        <div class="mb-2">
                            <span class="block text-sm font-medium text-gray-600 dark:text-gray-400">Resultado:</span>
                            <p class="text-sm font-semibold
                                {{ $item['pregunta']->isCorrectOption($item['respuesta']->respuesta) ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $item['pregunta']->isCorrectOption($item['respuesta']->respuesta) ? '✓ Correcto' : '✗ Incorrecto' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>


            <div class="pt-4">
                <button type="button"
                    wire:click="$set('mostrarModal', false)"
                    class="px-4 cursor-pointer py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-md transition">
                    Cerrar
                </button>
            </div>

        </div>
        @endif
    </flux:modal>




</div>
