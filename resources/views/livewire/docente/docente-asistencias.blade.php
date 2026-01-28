<div>
    {{-- parte superior --}}
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
                <flux:breadcrumbs.item href="{{ route('docente-asistencias') }}">Asistencias</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ $colegio->nombre ?? 'Sin Colegio' }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
        {{-- Botón Crear Grupo --}}
        {{--
        <div>
            <flux:modal.trigger wire:click="$set('modalCreacion', true)" name="crear-horario">
                <button class="h-12 px-6 bg-blue-600 text-white rounded-lg text-sm
                hover:bg-blue-700 transition duration-300 cursor-pointer
                dark:bg-blue-700 dark:hover:bg-blue-800">
                Crear Horario
                </button>
            </flux:modal.trigger>
        </div>
        --}}
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 my-8 space-y-8">
        {{-- TÍTULO Y SELECCIÓN --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white text-center">
                Registro de Asistencia
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- ASIGNATURA --}}
                <div>
                    <label for="asignatura_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Asignatura
                    </label>
                    <select wire:model.live="asignatura_id" id="asignatura_id"
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900
                               shadow-sm focus:border-blue-500 focus:ring-blue-500
                               dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500">
                        <option value="">-- Selecciona una asignatura --</option>
                        @if (isset($asignaturas) && count($asignaturas) > 0)

                        @foreach ($asignaturas as $a)
                        <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                        @endforeach
                        @else
                            <option value="">sin grupos</option>
                        @endif
                    </select>
                </div>

                {{-- GRUPO --}}
                <div>
                    <label for="grupo_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Grupo
                    </label>
                    <select wire:model.live="grupo_id" id="grupo_id"
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900
                               shadow-sm focus:border-blue-500 focus:ring-blue-500
                               dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500">
                        <option value="">-- Selecciona un grupo --</option>
                        @if (isset($grupos) && count($grupos) > 0)
                            @foreach ($grupos as $g)
                                <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                            @endforeach
                        @else
                            <option value="">sin grupos</option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="text-center mt-4 text-sm text-gray-600 dark:text-gray-300 space-y-1">
                <p><strong>Fecha:</strong> {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}</p>
                <p><strong>Asignatura:</strong> <span class="text-blue-600 dark:text-blue-400">{{ optional($asignatura)->nombre ?? 'Sin seleccionar' }}</span></p>
                <p><strong>Grupo:</strong> <span class="text-blue-600 dark:text-blue-400">{{ optional($grupo)->nombre ?? 'Sin seleccionar' }}</span></p>
            </div>
        </div>
        {{-- PLANILLA DE ASISTENCIA EN CARDS --}}
@if(count($estudiantes))
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 dark:bg-gray-800 ">
@foreach($estudiantes as $estudiante)

@php
    $estados = [
        'presente' => 'green',
        'ausente' => 'red',
        'tarde' => 'yellow',
        'justificado' => 'blue'
    ];
    $key = 'est_'.$estudiante->id;
    $estadoSeleccionado = $asistencias[$key]['estado'] ?? '';
@endphp

<div class="border p-4 rounded-lg">
    <p class="font-semibold mb-2">{{ $estudiante->nombre_completo }}</p>

    <div class="flex flex-wrap gap-2 mb-3">
        @foreach($estados as $estado => $color)
        <button type="button"
            wire:click="$set('asistencias.est_{{ $estudiante->id }}.estado', '{{ $estado }}')"
            class="px-3 py-1 text-xs rounded-full border cursor-pointer
                {{ $estadoSeleccionado === $estado
                    ? 'bg-'.$color.'-600 text-white'
                    : 'border-'.$color.'-500 text-'.$color.'-600' }}">
            {{ ucfirst($estado) }}
        </button>
        @endforeach
    </div>

    <input type="text"
        wire:model.live.debounce.300ms="asistencias.est_{{ $estudiante->id }}.justificacion"
        class="w-full border rounded p-2 text-sm cursor-pointer"
        placeholder="Justificación (opcional)">
</div>

@endforeach
</div>

<div class="mt-6 text-center">
    <button wire:click="guardarAsistencias"
        class="bg-blue-600 cursor-pointer text-white px-6 py-2 rounded">
        Guardar Asistencia
    </button>
</div>
@endif
    </div>

    {{-- js --}}
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
                });
            });
        </script>
    @endpush
</div>
