<div>
    {{-- apartado superior --}}
    <div class="flex items-center justify-between mb-6">
        {{-- Migajas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('colegio-grados') }}">Horarios</flux:breadcrumbs.item>
                @isset($colegio)
                    <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                @endisset
            </flux:breadcrumbs>
        </div>

        {{-- Botón Crear Grupo --}}
        @if (!empty($grupo_id))

        <div>
            <flux:modal.trigger wire:click="$set('modalCreacion', true)" name="crear-horario">
                <button class="h-12 px-6 bg-blue-600 text-white rounded-lg text-sm
                hover:bg-blue-700 transition duration-300 cursor-pointer
                dark:bg-blue-700 dark:hover:bg-blue-800">
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
                    <th class="p-3">Sabado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                @for ($hora = strtotime('06:00'); $hora <= strtotime('14:00'); $hora += 3600) {{-- cada 30 minutos --}}
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
                                        <div class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 p-2 rounded-lg shadow-inner">
                                            <strong>{{ $horario->asignatura->nombre ?? 'Materia' }}</strong><br>
                                            {{ $horario->profesor->nombre_completo }}
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
    @else
    @if ($grupo_id != null)
    <div class="col-span-full text-center text-gray-500 dark:text-gray-400">
        No hay Horarios hechos.
    </div>
    @endif
    @endif

    <flux:modal name="crear-horario" wire:model="modalCreacion" class="md:w-96 lg:w-10/12">
        <div class="space-y-6">
            {{-- Titulo Modal --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Creacion del Horario</h2>
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

            <div>
                {{-- Profesores --}}
                <div class="mb-4">
                    <label for="profesor_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Docente</label>
                    <select id="profesor_id" wire:model.live="profesor_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @if (isset($profesores) && count($profesores) > 0)
                            <option value="">Selecciona un docente</option>
                            @foreach ($profesores as $profesor )
                            <option value="{{ $profesor->id }}">{{ $profesor->nombre_completo }} {{ $profesor->documento }}</option>
                            @endforeach
                            @else
                            <option selected value="">Sin Docentes</option>
                            @endif
                    </select>
                    @error('profesor_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Asignaturas --}}
                <div class="mb-4">
                    <label for="asignatura_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Docente</label>
                    <select id="asignatura_id" wire:model.defer="asignatura_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @if (isset($asignaturas) && count($asignaturas) > 0)
                            <option value="">Selecciona un docente</option>
                            @foreach ($asignaturas as $asignatura )
                            <option value="{{ $asignatura->asignatura->id }}">{{ $asignatura->asignatura->nombre }} {{ $asignatura->asignatura->codigo }}</option>
                            @endforeach
                            @else
                            <option selected value="">Sin Asignaturas disponibles</option>
                            @endif
                    </select>
                    @error('profesor_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                {{-- grupos --}}
                <div class="mb-4">
                    <label for="grupo_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Grupo</label>
                    <select id="grupo_id" wire:model.defer="grupo_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @if (isset($grupos) && count($grupos) > 0)
                            <option value="">Selecciona un grupo</option>
                            @foreach ($grupos as $grupo )
                            <option value="{{ $grupo->id }}">{{ $grupo->nombre }} {{ $grupo->grado->nombre }}</option>
                            @endforeach
                            @else
                            <option selected value="">Sin Docentes</option>
                            @endif
                    </select>
                    @error('grupo_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Dia --}}
                <div class="mb-4">
                    <label for="dia" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dia</label>
                    <select id="dia" wire:model.defer="dia"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">Seleccione el dia</option>
                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miercoles">Miercoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                            <option value="Sabado">Sabado</option>
                    </select>
                    @error('dia')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- hora inicio y fin --}}
                <div class="mb-4 flex justify-between">
                    <div class="relative">
                        <label for="hora_inicio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hora Inicio</label>
                        <div  class="relative">
                            <input type="time" wire:model.defer="hora_inicio" id="hora_inicio"
                                class=" pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700
                                dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500
                                dark:focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                                </svg>
                            </div>

                        </div>
                    </div>


                    <div class="relative ">
                        <label for="hora_fin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hora Fin</label>
                        <div  class="relative">
                            <input  type="time" wire:model.defer="hora_fin" id="hora_fin"
                                class=" pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700
                                dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500
                                dark:focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                                </svg>
                            </div>

                        </div>
                    </div>


                </div>
                @error('hora_inicio')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                @error('hora_fin')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
            </div>



            {{-- Footer modal --}}
            <div class="flex pt-4">
                <div class="flex-1"></div>
                <div class="flex space-x-3">
                        <button type="button" wire:click="crearHorario()"
                            class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 cursor-pointer">
                            Crear Horario
                        </button>
                </div>
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
