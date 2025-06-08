<div class="space-y-6">
    {{-- ENCABEZADO Y BOTÓN VOLVER --}}
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
                <flux:breadcrumbs.item href="{{ route('docente-actividades') }}">Actividades</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="#">Ver</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
        <a href="{{ route('docente-actividades') }}"
            class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition">
            Volver
        </a>
    </div>

    {{-- INFORMACIÓN DE LA ACTIVIDAD --}}
    <div class="bg-white dark:bg-gray-900 shadow-md rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
            {{ $actividad->titulo }}
        </h2>

        <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-300">
            <div>
                <p><span class="font-semibold">Asignatura:</span> {{ $actividad->asignatura->nombre ?? '—' }}</p>
                <p><span class="font-semibold">Grupo:</span> {{ $actividad->grupo->nombre ?? '—' }}</p>
            </div>
            <div>
                <p><span class="font-semibold">Fecha de entrega:</span> {{ \Carbon\Carbon::parse($actividad->fecha_entrega)->format('d/m/Y') }}</p>
                <p><span class="font-semibold">Creado:</span> {{ $actividad->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="mt-4">
            <p class="text-sm text-gray-600 dark:text-gray-400"><span class="font-semibold">Descripción:</span></p>
            <p class="text-gray-800 dark:text-gray-200">{{ $actividad->descripcion }}</p>
        </div>

        @if ($actividad->archivo)
            <div class="mt-4">
                <a href="{{ asset('storage/' . $actividad->archivo) }}" target="_blank"
                    class="text-blue-600 dark:text-blue-300 underline text-sm hover:opacity-80 transition">
                    Ver archivo adjunto
                </a>
            </div>
        @endif
    </div>

    {{-- RESPUESTAS DE LOS ESTUDIANTES --}}
    <div>
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
            Respuestas de los estudiantes
        </h3>

        @if ($actividad->respuestas->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($actividad->respuestas as $respuesta)
                    <div class="bg-white dark:bg-gray-900 p-4 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                        {{-- Nombre del estudiante y entrega --}}
                        <div class="mb-2">
                            <p class="font-semibold text-gray-800 dark:text-white text-sm">
                                {{ $respuesta->estudiante->nombre_completo ?? 'Estudiante desconocido' }}
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($respuesta->created_at)->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        {{-- Archivo entregado --}}
                        @if ($respuesta->archivo)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $respuesta->archivo) }}" target="_blank"
                                    class="text-xs text-blue-600 dark:text-blue-300 underline hover:opacity-80">
                                    Ver archivo
                                </a>
                            </div>
                        @endif

                        {{-- Contenido textual --}}
                        @if ($respuesta->contenido)
                            <div class="text-xs text-gray-700 dark:text-gray-200 whitespace-pre-wrap border-t pt-2 border-gray-200 dark:border-gray-700 mb-2">
                                {{ Str::limit($respuesta->contenido, 100) }}
                            </div>
                        @endif

                        {{-- Input de calificación --}}
                        <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                            <label for="calificacion_{{ $respuesta->id }}"
                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Calificación:
                            </label>
                            <div class="flex items-center gap-2">
                                <input
                                    id="calificacion_{{ $respuesta->id }}"
                                    type="number"
                                    wire:model.live="notas.{{ $respuesta->estudiante->id }}_{{ $respuesta->actividad->id }}"
                                    wire:key="nota-{{ $respuesta->estudiante->id }}-{{ $respuesta->actividad->id }}"
                                    min="0"
                                    max="100"
                                    step="0.1"
                                    class="w-20 px-1 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-100"
                                />

                                <button
                                    wire:click="subirNota"
                                    class="px-2 cursor-pointer py-1 bg-green-600 text-white rounded hover:bg-green-700 text-xs"
                                >
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400 text-sm">
                Aún no hay respuestas registradas para esta actividad.
            </p>
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
