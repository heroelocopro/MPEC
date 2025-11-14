<div>
    {{-- apartado superior --}}
    <div class="flex items-center justify-between mb-6">
        {{-- Migajas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('login') }}">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('colegio-grados') }}">Horarios</flux:breadcrumbs.item>
                @isset($colegio)
                    <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                @endisset
            </flux:breadcrumbs>
        </div>

        {{-- Botón Crear Horario --}}
        @if (!empty($grupo_id))
            <div>
                <flux:modal.trigger wire:click="$set('modalCreacion', true)" name="crear-horario">
                    <button class="h-12 animate-slide-in-top px-6 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition duration-300 cursor-pointer dark:bg-blue-700 dark:hover:bg-blue-800">
                        Crear Horario
                    </button>
                </flux:modal.trigger>
            </div>
        @endif
    </div>

    {{-- Título --}}
    <h2 class="text-center text-lg font-semibold text-gray-800 dark:text-white mb-4">Selecciona un Grupo</h2>

    {{-- Grid de tarjetas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4">
        @forelse($grupos as $grupo)
            <div
                wire:click="$set('grupo_id', {{ $grupo->id }})"
                class="cursor-pointer p-4 border rounded-xl shadow-sm transition hover:shadow-md
                    {{ $grupo_id == $grupo->id ? 'border-blue-500 bg-blue-100 dark:bg-blue-800 dark:border-blue-400 text-blue-800 dark:text-white' : 'bg-white dark:bg-neutral-800 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white' }}"
            >
                <div class="text-lg font-semibold">{{ $grupo->nombre }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-300">Grado: {{ $grupo->grado->nombre }}</div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 dark:text-gray-400">
                No hay grupos disponibles.
            </div>
        @endforelse
    </div>
    <p>Los horarios van en pares. Borrar 1 es equivalente a borrar a su pareja.</p>

    {{-- horario --}}
    @if (isset($horarios) && count($horarios) > 0)
        <div class="overflow-x-auto rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full text-sm text-center text-gray-900 dark:text-white">
                <thead class="bg-blue-600 text-white dark:bg-blue-800">
                    <tr>
                        <th class="p-3 w-24 bg-blue-700 dark:bg-blue-900">Hora</th>
                        <th class="p-3">Lunes</th>
                        <th class="p-3">Martes</th>
                        <th class="p-3">Miércoles</th>
                        <th class="p-3">Jueves</th>
                        <th class="p-3">Viernes</th>
                        <th class="p-3">Sábado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @for ($hora = strtotime('06:00'); $hora <= strtotime('14:00'); $hora += 1800)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-800">
                            <td class="p-2 bg-gray-100 dark:bg-gray-900 font-medium">
                                {{ date('H:i', $hora) }}
                            </td>
                            @php
                                $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
                            @endphp
                            @foreach ($dias as $dia)
                                <td class="p-3">
                                    @foreach ($horarios as $horario)
                                        @php
                                            $inicio = strtotime($horario->hora_inicio);
                                            $fin = strtotime($horario->hora_fin);
                                        @endphp
                                        @if ($horario->dia === $dia && $hora >= $inicio && $hora <= $fin)
                                        <div class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 p-2 rounded-lg shadow-inner relative">
                                            <strong>{{ $horario->asignatura->nombre ?? 'Materia' }}</strong><br>
                                            {{ $horario->profesor->nombre_completo }}

                                            {{-- Botón eliminar --}}
                                            <button
                                                wire:click="eliminarHorario({{ $horario->id }})"
                                                class="absolute top-1 right-1 text-red-500 hover:text-red-700 dark:hover:text-red-400"
                                                title="Eliminar horario"
                                            >
                                                &times;
                                            </button>
                                        </div>

                                        @endif
                                    @endforeach
                                </td>
                            @endforeach
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    @elseif($grupo_id)
        <div class="col-span-full text-center text-gray-500 dark:text-gray-400">
            No hay Horarios hechos.
        </div>
    @endif

    {{-- Modal de Creación --}}
    <flux:modal name="crear-horario" wire:model="modalCreacion" class="md:w-96 lg:w-10/12 animate-fade-in-up">
        <div class="space-y-6">
            {{-- Título Modal --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Creación del Horario</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Asigne toda la información requerida.</p>
            </div>

            {{-- Mostrar errores generales --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 dark:bg-red-800/30">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500 dark:text-red-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Hay {{ $errors->count() }} error(es) en el formulario
                            </h3>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Campos del formulario --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Docente --}}
                <div>
                    <label for="profesor_id" class="block text-sm font-medium text-gray-700 dark:text-white">Docente</label>
                    <select id="profesor_id" wire:model.live="profesor_id"
                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Selecciona un docente</option>
                        @foreach ($profesores as $profesor)
                            <option value="{{ $profesor->id }}">{{ $profesor->nombre_completo }}</option>
                        @endforeach
                    </select>
                    @error('profesor_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Asignatura --}}
                <div>
                    <label for="asignatura_id" class="block text-sm font-medium text-gray-700 dark:text-white">Asignatura</label>
                    <select id="asignatura_id" wire:model.defer="asignatura_id"
                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Selecciona una asignatura</option>
                        @foreach ($asignaturas as $asignatura)
                            <option value="{{ $asignatura->asignatura->id }}">{{ $asignatura->asignatura->nombre }}</option>
                        @endforeach
                    </select>
                    @error('asignatura_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Día --}}
                <div>
                    <label for="dia" class="block text-sm font-medium text-gray-700 dark:text-white">Día</label>
                    <select id="dia" wire:model.defer="dia"
                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Selecciona un día</option>
                        @foreach (['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'] as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                    @error('dia') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Horario Inicio --}}
                <div>
                    <label for="hora_inicio" class="block text-sm font-medium text-gray-700 dark:text-white">Hora Inicio</label>
                    <input type="time" id="hora_inicio" wire:model.defer="hora_inicio"
                           class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('hora_inicio') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Horario Fin --}}
                <div>
                    <label for="hora_fin" class="block text-sm font-medium text-gray-700 dark:text-white">Hora Fin</label>
                    <input type="time" id="hora_fin" wire:model.defer="hora_fin"
                           class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('hora_fin') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex justify-end space-x-3">
                <button type="button" wire:click="$set('modalCreacion', false)"
                        class="px-4 cursor-pointer py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                    Cancelar
                </button>
                <button type="submit" wire:click="crearHorario"
                        class="px-4 cursor-pointer py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Guardar
                </button>
            </div>
        </div>
    </flux:modal>

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
