<div>
    {{-- parte superior --}}
    <div class="flex items-center justify-between mb-6">
        {{-- Migas de pan --}}
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
                <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
    </div>

    {{-- Mostrar Actividades --}}
    <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-xl space-y-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white text-center">Gestión de Actividades</h2>

        {{-- Botones para Crear y Ver Actividades --}}
        <div class="flex justify-between mb-6">
            <button wire:click="$set('mostrarFormulario',true)"
                    class="bg-blue-600 cursor-pointer hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition">
                Crear Actividad
            </button>

            <button wire:click="$set('mostrarActividades',true)"
                    class="bg-green-600 cursor-pointer hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition">
                Ver Actividades
            </button>
        </div>

        {{-- Formulario de Actividad (visible solo si se desea crear) --}}
        @if($mostrarFormulario)
        <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-xl space-y-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Subir Actividad</h2>

            {{-- Selección de Asignatura --}}
            <div>
                <label class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">Asignatura</label>
                <select wire:model.live="asignatura_id"
                        class="w-full rounded-lg shadow-sm border border-gray-300 dark:border-gray-600
                               bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100 focus:ring focus:ring-blue-500">
                    <option value="">Selecciona una asignatura</option>
                    @foreach ($asignaturas as $a)
                        <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Selección de Grupo --}}
            <div>
                <label class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">Grupo</label>
                <select wire:model.live="grupo_id"
                        class="w-full rounded-lg shadow-sm border border-gray-300 dark:border-gray-600
                               bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100 focus:ring focus:ring-blue-500">
                    <option value="">Selecciona un grupo</option>
                    @foreach ($grupos as $g)
                        <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                    @endforeach
                </select>
                @error('grupo_id')
                    <h3 class="text-red-500">{{ $message }}</h3>
                @enderror
            </div>

            {{-- Formulario de Actividad --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">Título</label>
                    <input type="text" wire:model.live="titulo"
                           class="w-full rounded-lg shadow-sm border border-gray-300 dark:border-gray-600
                                  bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100 focus:ring focus:ring-blue-500" />
                </div>

                <div>
                    <label class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">Fecha de Entrega</label>
                    <input type="date" wire:model.live="fecha_entrega"
                           class="w-full rounded-lg shadow-sm border border-gray-300 dark:border-gray-600
                                  bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100 focus:ring focus:ring-blue-500" />
                    @error('fecha_entrega')
                    <h3 class="text-red-500">{{ $message }}</h3>
                    @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">Descripción</label>
                    <textarea wire:model.live="descripcion" rows="4"
                              class="w-full rounded-lg shadow-sm border border-gray-300 dark:border-gray-600
                                     bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100 focus:ring focus:ring-blue-500"></textarea>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">Archivo</label>
                    <input type="file" wire:model.live="archivo"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                                  bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100 shadow-sm" />
                </div>
            </div>

            {{-- Botón para Guardar Actividad --}}
            <div class="text-right">
                <button wire:click="crearActividad"
                        class="bg-blue-600 cursor-pointer hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition">
                    Crear Actividad
                </button>
            </div>
        </div>
        @endif

        @if ($mostrarActividades)
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-6 space-y-6">

                {{-- Filtro por grupo --}}
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Actividades por Grupo</h2>

                    <div>
                        <label class="text-gray-700 dark:text-gray-300 text-sm font-medium block mb-1">Filtrar por grupo:</label>
                        <select wire:model.live="grupoFiltro"
                                class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-4 py-2">
                            <option value="">Todos los grupos</option>
                            @foreach ($gruposFiltro as $grupo)
                                <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Actividades agrupadas por grupo --}}
                @if (isset($actividades) && count($actividades) > 0)
                @foreach ($actividades->groupBy('grupo_id') as $grupoId => $actividadesDelGrupo)
                    @if ($grupoFiltro == '' || $grupoFiltro == $grupoId)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-2xl font-semibold text-blue-700 dark:text-blue-400 mb-4">
                                Grupo: {{ $actividadesDelGrupo->first()->grupo->nombre }}
                            </h3>

                            <div class="grid gap-6 md:grid-cols-2">
                                @foreach ($actividadesDelGrupo as $actividad)
                                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-200 dark:border-gray-700 relative">
                                        <h4 class="text-xl font-semibold text-gray-800 dark:text-white mb-1">
                                            {{ $actividad->titulo }}
                                        </h4>
                                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-2">
                                            {{ $actividad->descripcion }}
                                        </p>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                                            <span class="font-medium">Asignatura:</span> {{ $actividad->asignatura->nombre ?? '—' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                            <span class="font-medium">Entrega:</span> {{ \Carbon\Carbon::parse($actividad->fecha_entrega)->format('d/m/Y') }}
                                        </div>

                                        @if ($actividad->archivo)
                                            <a href="{{ asset('storage/' . $actividad->archivo) }}" target="_blank"
                                            class="text-blue-600 dark:text-blue-300 underline text-sm hover:opacity-80 transition">
                                                Ver archivo adjunto
                                            </a>
                                        @endif

                                        {{-- Botones --}}
                                        <div class="mt-4 flex justify-between items-center">
                                            <!-- Botón izquierdo -->
                                            {{-- <button wire:click="verRespuestas({{ $actividad->id }})"
                                                    class="px-3 cursor-pointer py-1 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                                Ver respuestas
                                            </button> --}}
                                            <a class="px-3 cursor-pointer py-1 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg" href="{{ route('docente-ver-actividad',$actividad->id) }}">
                                                Ver respuestas
                                            </a>

                                            <!-- Botones derechos -->
                                            <div class="flex gap-3">
                                                <button wire:click="editarActividad({{ $actividad->id }})"
                                                        class="px-3 cursor-pointer py-1 text-sm bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg">
                                                    Editar
                                                </button>
                                                <button wire:click="eliminarActividad({{ $actividad->id }})"
                                                        class="px-3 cursor-pointer py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg">
                                                    Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
                @else
                @if (empty($actividades))
                <p class="text-center text-gray-600 dark:text-gray-300">No hay actividades registradas.</p>
                @endif
                @endif

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
