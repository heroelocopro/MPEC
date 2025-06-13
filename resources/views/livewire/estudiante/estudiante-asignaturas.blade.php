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

<div>
    {{-- Notificaciones fijas a la derecha superior --}}
    <div class="fixed top-4 right-4 z-50 cursor-pointer">
        <livewire:notificaciones />
    </div>
    {{-- Parte Superior --}}
    <div class="flex items-center justify-between mb-6">
        {{-- Migas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('estudiante-asignaturas') }}">Asignaturas</flux:breadcrumbs.item>
                @isset($colegio)
                <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                @endisset
                @isset($grupo)
                <flux:breadcrumbs.item>{{ $grupo->nombre }}</flux:breadcrumbs.item>
                @endisset
            </flux:breadcrumbs>
        </div>
    </div>

    {{-- Parte Principal --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-4">
        @foreach($asignaturas as $asignatura)
            @php
                $icono = $iconos[$asignatura->nombre] ?? strtoupper(substr($asignatura->nombre, 0, 1));
            @endphp
            <div class="bg-gradient-to-tr from-white to-blue-50 dark:from-gray-800 dark:to-blue-900 rounded-3xl shadow-lg border border-blue-200 dark:border-blue-800 p-6 flex flex-col justify-between hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center gap-4 mb-5">
                    {{-- Ícono o letra --}}
                    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-300 dark:bg-blue-700 text-blue-900 dark:text-blue-200 text-xl font-extrabold select-none">
                        {{ $icono }}
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white leading-tight">{{ $asignatura->nombre }}</h3>
                        @if ($asignatura->profesores->isEmpty())
                            <p class="text-sm italic text-gray-500 dark:text-gray-400 mt-1">Sin profesor asignado</p>
                        @else
                            @foreach ($asignatura->profesores as $profesor)
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $profesor->nombre_completo }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="text-sm text-gray-700 dark:text-gray-300 space-y-1 mb-4">
                    <p><strong>Grados:</strong>
                        @foreach ($asignatura->asignaturaGrados as $asignaturaGrado)
                        @if ($grupo->grado->id == $asignaturaGrado->grado->id)
                                                    <span>{{ $asignaturaGrado->grado->nombre ?? 'N/A' }}</span>@if (!$loop->last), @endif
                        @endif
                        @endforeach
                    </p>
                    <p><strong>Horarios:</strong></p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 max-h-24 overflow-auto">
                        @foreach ($asignatura->horarios as $horario)
                        @if ($horario->grupo_id == $grupo->id)
                        <li>
                            {{ ucfirst($horario->dia) }} {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div>

                {{-- boton para ver detalles futuro --}}
                {{-- <div class="mt-auto">
                    <flux:button
                        class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-semibold rounded-xl py-2 transition-colors"
                        wire:click="verDetalles({{ $asignatura->id }})">
                        Ver detalles
                    </flux:button>
                </div> --}}
            </div>
        @endforeach
    </div>
</div>
