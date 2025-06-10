<div>
    {{-- Notificaciones fijas a la derecha superior --}}
    <div class="fixed top-4 right-4 z-50 cursor-pointer">
        <livewire:notificaciones />
    </div>
    {{-- Parte Superior --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('estudiante-examenes') }}">Exámenes</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ $grupo->nombre }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
    </div>

    {{-- Exámenes agrupados --}}
    <div class="space-y-8">
        @php
            $iconos = [
                'Matemáticas' => '🧮',
                'Lenguaje' => '📚',
                'Ciencias' => '🧪',
                'Arte' => '🎭',
                'Música' => '🎶',
                'Inglés' => '🗣️',
                'Educación Física' => '🤸‍♂️',
                'Historia' => '🏺',
                'Geografía' => '🗺️',
                'Informática' => '🖥️',
                'Religión' => '✝️',
            ];
        @endphp

        @foreach ($examenesAsignaturas as $index => $asignatura)
            <section class="bg-white dark:bg-gray-900 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 p-6">
                <header class="flex items-center space-x-4 mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-indigo-600 rounded-full text-white text-2xl select-none">
                        {{ $iconos[$asignatura[0]] ?? '📖' }}
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $asignatura[0] }}</h2>
                </header>

                @if (empty($asignatura[1]) || count($asignatura[1]) == 0)
                    <p class="text-gray-600 dark:text-gray-400 italic">No hay exámenes disponibles para esta asignatura.</p>
                @else
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($asignatura[1] as $examen)
                            <li class="flex items-center justify-between py-3 hover:bg-indigo-50 dark:hover:bg-indigo-800 rounded-md transition-colors">
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-gray-200 truncate max-w-xs">
                                        📝 {{ $examen->titulo }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                        Fecha de vencimiento: {{ \Carbon\Carbon::parse($examen->fecha_vencimiento)->format('d/m/Y') }}
                                    </p>
                                </div>

                                <div>
                                    <flux:modal.trigger wire:click="cargarExamen({{ $examen->id }})" name="ver-examen">
                                        <button class="inline-block cursor-pointer bg-indigo-600 text-white text-sm px-4 py-1.5 rounded-full shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                                            Ver Examen
                                        </button>
                                    </flux:modal.trigger>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        @endforeach
    </div>

    {{-- Modal para ver el examen --}}
    <flux:modal wire:model.live="verModal" name="ver-examen" class="max-w-xl lg:max-w-2xl">
        <div class="p-6 space-y-6 bg-white dark:bg-gray-900 rounded-lg">
            <div>
                <flux:heading size="lg" class="text-indigo-700 dark:text-indigo-300 font-semibold">Detalle del Examen</flux:heading>
                <flux:text class="mt-1 text-gray-600 dark:text-gray-400">Información completa del examen seleccionado.</flux:text>
            </div>

            @if ($cargandoExamen)
                <div class="flex flex-col items-center py-16 text-indigo-600 dark:text-indigo-400">
                    <svg class="animate-spin h-10 w-10 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <circle class="opacity-25" cx="12" cy="12" r="10"/>
                        <path class="opacity-75" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                    </svg>
                    <span class="text-lg font-medium">Cargando...</span>
                </div>
            @elseif (!empty($examenModal))
                <div class="space-y-5">
                    <div>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">Título:</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $examenModal->titulo }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">Fecha:</p>
                            <p class="text-base text-gray-900 dark:text-gray-100">
                                {{ \Carbon\Carbon::parse($examenModal->fecha)->format('d/m/Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">Asignatura:</p>
                            <p class="text-base text-gray-900 dark:text-gray-100">{{ $examenModal->asignatura->nombre ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        @if ($examenHecho)
                            <button disabled class="px-5 py-2 rounded-full bg-gray-300 dark:bg-gray-700 text-gray-600 dark:text-gray-400 cursor-not-allowed">
                                Examen ya realizado
                            </button>
                        @else
                            <button wire:click="iniciarExamen({{ $examenModal->id }})" class="px-5 cursor-pointer py-2 rounded-full bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                Iniciar Examen
                            </button>
                        @endif

                        <button wire:click="$set('verModal', false)" class="px-5 cursor-pointer py-2 rounded-full border border-indigo-600 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-800 transition">
                            Cerrar
                        </button>
                    </div>
                </div>
            @else
                <p class="text-center text-gray-500 dark:text-gray-400 italic py-10">No se ha seleccionado ningún examen.</p>
            @endif
        </div>
    </flux:modal>
</div>
