<div>
    {{-- Breadcrumbs --}}
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <flux:breadcrumbs>
                    <flux:breadcrumbs.item href="{{ route('login') }}">Panel</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>{{ $colegio->nombre ?? 'Sin nombre' }}</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item href="{{ route('colegio-historial-academico') }}">Historial Académico</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
        </div>
    </div>



    {{-- Filtros --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
        <input wire:model.live="search" type="text" placeholder="Buscar estudiante..." class="w-full md:w-1/3 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-sm text-gray-800 dark:text-white">
        <div>

            <select wire:model.live="paginate" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-sm text-gray-800 dark:text-white">
                <option value="5">5 paginacion</option>
                <option value="10">10 paginacion</option>
                <option value="25">25 paginacion</option>
            </select>
            <select wire:model.live="gradoFiltro" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-sm text-gray-800 dark:text-white">
                <option value="">Todos</option>
                @foreach ($grados as $grado)
                {{-- <option value="{{ $grado->id }}">{{ $grado->id }}</option> --}}
                <option value="{{ $grado->id }}" {{ $gradoSeleccionado != null && $grado->id === $gradoSeleccionado->id  ? 'selected' : '' }}>{{ $grado->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Tarjetas de estudiantes --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($estudiantes as $est)
            <div wire:click="seleccionarEstudiante({{ $est->id }})" class="bg-white border-2 hover:border-2 hover:border-blue-500 dark:bg-gray-900 p-4 rounded-xl shadow-md cursor-pointer hover:shadow-lg transition-all">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $est->nombre_completo }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">Documento: {{ $est->documento }} </p>
                <p class="text-sm text-gray-600 dark:text-gray-300">Grado: {{ $est->matricula->grado->nombre }} </p>
                <p class="text-sm text-gray-600 dark:text-gray-300">Promedio: {{ $est->promedio($est->estudiantesGrupos->first()->grupo->id ?? 0) ?? 'sin notas' }} </p>
                <p class="text-sm text-gray-600 dark:text-gray-300">Asistencias: {{ $est->asistenciasTotales }} </p>
                <p class="mt-2 text-sm text-blue-600 dark:text-blue-400 font-medium">Ver detalles</p>
            </div>
        @endforeach
    </div>
        <div class="space-y-6 mt-5">
            {{ $estudiantes->links() }}
        </div>

    {{-- Modal del estudiante --}}
    @if ($mostrarModal && isset($estudianteSeleccionado))
        <div class="fixed inset-0  bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow-lg w-full max-w-3xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Detalle de {{ $estudianteSeleccionado->nombre_completo }} </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Promedio general: <span class="font-semibold">{{ $estudianteSeleccionado->promedio($estudianteSeleccionado->estudiantesGrupos->first()->grupo->id ?? 0) ?? 'sin notas' }}</span></p>
                    </div>
                    <button wire:click="$set('mostrarModal',false)" class="text-gray-500 cursor-pointer dark:text-gray-400 hover:text-red-500 text-3xl font-bold">&times;</button>
                </div>

                {{-- Selector de período --}}
                <div class="mb-4">
                    <select wire:model.live="periodo_id" class="px-4 cursor-pointer py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-sm text-gray-800 dark:text-white">
                        @foreach ($periodos as $p)
                            <option value="{{ $p->id }}" {{ $periodoSeleccionado != null &&  $p->id == $periodoSeleccionado->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Información personal --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-300 mb-6">
                    <p><strong>Documento:{{ $estudianteSeleccionado->documento }}</strong> </p>
                    <p><strong>Correo:{{ $estudianteSeleccionado->correo }}</strong> </p>
                    <p><strong>Edad:{{ $estudianteSeleccionado->edad() }}</strong> </p>
                    <p><strong>Grado:{{ $estudianteSeleccionado->direccion }}</strong>
                    <p><strong>Grado:{{ $estudianteSeleccionado->matricula->grado->nombre }}</strong>
                    <p><strong>Asistencia:{{ $estudianteSeleccionado->asistenciasTotales }}</strong> </p>
                </div>

                {{-- Tabla de notas --}}
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-3">Notas - {{ $periodoSeleccionado != null ? $periodoSeleccionado->nombre : 'sin periodo' }}</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto text-sm border rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-2 text-left">Asignatura</th>
                                <th class="px-4 py-2 text-left">Nota</th>
                                <th class="px-4 py-2 text-left">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                            @if (isset($estudianteSeleccionado) && isset($notasFinales))
                            @foreach ($notasFinales as $nota)
                                <tr class="border-t border-gray-200 dark:border-gray-700">
                                    <td class="px-4 py-2">{{ $nota->asignatura->nombre }}</td>
                                    <td class="px-4 py-2">{{ $nota->nota }}</td>
                                    <td class="px-4 py-2">
                                        @if ($nota->nota >= $notaMinima->nota_minima)
                                            <span class="text-green-600 dark:text-green-400">Aprobado</span>
                                        @else
                                            <span class="text-red-600 dark:text-red-400">Reprobado</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Botón para descargar --}}
                <div class="mt-6">
                    <button wire:click="descargarNotas" class="bg-blue-600 cursor-pointer hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        Descargar Reporte PDF
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
