<div>
    {{-- Parte Superior --}}
    <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
        {{-- Migajas de pan --}}
        <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#" class="hover:underline">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('administrador-colegios') }}" class="hover:underline">Colegios</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-4">
        {{-- Select de paginación --}}
        <div class="w-full md:w-[10%]">
            <select wire:model.live="pagination" class="w-full bg-white text-black border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-100">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        {{-- Búsqueda --}}
        <div class="w-full md:w-[80%]">
            <input type="text" wire:model.live.debounce.500ms="search" placeholder="Buscar colegio..."
                class="w-full border-gray-300  bg-white text-black rounded-md dark:bg-gray-800 dark:text-gray-100"
            />
        </div>

        {{-- Botón crear --}}
        <div class="w-full md:w-[10%]">
            <button wire:click="crearColegio"
                class="w-full cursor-pointer bg-green-500  text-white px-3 py-2 rounded hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700">
                Crear Colegio
            </button>
        </div>
    </div>

    {{-- Tabla --}}
{{-- Tabla --}}
<div class="w-full overflow-x-auto rounded-md">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                @foreach ([
                    'id' => 'ID',
                    'nombre' => 'Nombre',
                    'codigo_dane' => 'Código DANE',
                    'direccion' => 'Dirección',
                    'telefono' => 'Teléfono',
                    'correo' => 'Correo',
                    'departamento' => 'Departamento',
                    'municipio' => 'Municipio',
                    'estado' => 'Estado',
                    'calendario' => 'Calendario',
                ] as $campo => $label)
                    <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 cursor-pointer whitespace-nowrap"
                        wire:click="sortBy('{{ $campo }}')">
                        {{ $label }}
                        @if($sortField === $campo)
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                @endforeach

                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">Sedes</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">Acciones</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
            @forelse ($colegios as $colegio)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $colegio->id }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $colegio->nombre }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $colegio->codigo_dane }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $colegio->direccion }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $colegio->telefono }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $colegio->usuario->email }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $colegio->departamento }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $colegio->municipio }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $colegio->estado }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $colegio->calendario }}</td>

                    <td class="px-4 py-3 whitespace-nowrap">
                        <button wire:click="mostrarSedes({{ $colegio->id }})"
                            class="bg-purple-500 text-white px-3 py-1 rounded hover:bg-purple-600 text-xs dark:bg-purple-600 dark:hover:bg-purple-700 cursor-pointer">
                            Ver Sedes
                        </button>
                    </td>

                    <td class="px-4 py-3 space-x-2 whitespace-nowrap">
                        <a href="{{ route('administrador-colegio',$colegio->id) }}"
                            class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-xs dark:bg-green-600 dark:hover:bg-green-700 cursor-pointer">
                            Ver
                        </a>
                        <button wire:click="editarColegio({{ $colegio->id }})"
                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs dark:bg-blue-600 dark:hover:bg-blue-700 cursor-pointer">
                            Editar
                        </button>
                        <button wire:click="$dispatch('confirmarEliminarColegio', { id: {{ $colegio->id }} })"
                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-xs dark:bg-red-600 dark:hover:bg-red-700 cursor-pointer">
                            Eliminar
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="15" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                        No se encontraron colegios registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


    {{-- modal Creacion del colegio --}}
    {{-- Modal para crear un colegio --}}
    <flux:modal wire:model.defer="modalCrear" class="!max-w-4xl w-full">
        <div class="px-6 pt-5 pb-6 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-4">Crear Nuevo Colegio</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nombre</label>
                    <input type="text" wire:model.defer="colegio.nombre"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Código DANE</label>
                    <input type="text" wire:model.defer="colegio.codigo_dane"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Dirección</label>
                    <input type="text" wire:model.defer="colegio.direccion"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Teléfono</label>
                    <input type="text" wire:model.defer="colegio.telefono"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Correo</label>
                    <input type="email" wire:model.defer="colegio.correo"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Departamento</label>
                    <input type="text" wire:model.defer="colegio.departamento"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Municipio</label>
                    <input type="text" wire:model.defer="colegio.municipio"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Estado</label>
                    <select wire:model.defer="colegio.estado"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Seleccione</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Calendario</label>
                    <select wire:model.defer="colegio.calendario"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Seleccione</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                    </select>
                </div>
            </div>

            {{-- Botones --}}
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" wire:click="$set('modalCrear', false)"
                    class="px-4 py-2 cursor-pointer bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancelar
                </button>

                <button type="button" wire:click="guardarColegio"
                    class="px-4 py-2 cursor-pointer bg-blue-600 hover:bg-blue-700 text-white rounded transition">
                    Guardar
                </button>
            </div>
        </div>
    </flux:modal>

    <flux:modal wire:model.defer="modalEditar" class="!max-w-4xl w-full">
        <div class="px-6 pt-5 pb-6 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-4">Editar Colegio</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nombre</label>
                    <input type="text" wire:model.defer="colegioEditar.nombre"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Código DANE</label>
                    <input type="text" wire:model.defer="colegioEditar.codigo_dane"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Dirección</label>
                    <input type="text" wire:model.defer="colegioEditar.direccion"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Teléfono</label>
                    <input type="text" wire:model.defer="colegioEditar.telefono"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Correo</label>
                    <input type="email" wire:model.defer="colegioEditar.correo"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Departamento</label>
                    <input type="text" wire:model.defer="colegioEditar.departamento"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Municipio</label>
                    <input type="text" wire:model.defer="colegioEditar.municipio"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Estado</label>
                    <select wire:model.defer="colegioEditar.estado"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Seleccione</option>
                        <option value="NUEVO-ACTIVO">NUEVO-ACTIVO</option>
                        <option value="ANTIGUO-ACTIVO">ANTIGUO-ACTIVO</option>
                        <option value="ANTIGUO-INACTIVO">ANTIGUO-INACTIVO</option>
                        <option value="NUEVO-INACTIVO">NUEVO-INACTIVO</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Calendario</label>
                    <select wire:model.defer="colegioEditar.calendario"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Seleccione</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                    </select>
                </div>
            </div>

            {{-- Botones --}}
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" wire:click="$set('modalEditar', false)"
                    class="px-4 cursor-pointer py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancelar
                </button>

                <button type="button" wire:click="actualizarColegio"
                    class="px-4 cursor-pointer py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition">
                    Actualizar
                </button>
            </div>
        </div>
    </flux:modal>


    {{-- modal sedes del colegio --}}
    <flux:modal wire:model.defer="modalSedes" class="!max-w-7xl w-full">
    <div class="px-6 pt-5">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
            Sedes del Colegio: {{ $colegioSeleccionado?->nombre ?? '' }}
        </h2>

        @if($sedes && count($sedes))
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            @foreach ([
                                'id' => 'ID',
                                'nombre' => 'Nombre',
                                'codigo_dane' => 'Código DANE',
                                'direccion' => 'Dirección',
                                'telefono' => 'Teléfono',
                                'correo' => 'Correo',
                                'departamento' => 'Departamento',
                                'municipio' => 'Municipio',
                                'estado' => 'Estado',
                                'calendario' => 'Calendario',
                                'colegio_id' => 'ID Colegio'
                            ] as $campo => $label)
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    {{ $label }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:bg-gray-800 dark:divide-gray-700">
                        @foreach($sedes as $sede)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $sede->id }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $sede->nombre }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $sede->codigo_dane }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $sede->direccion }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $sede->telefono }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $sede->correo ? null : 'NA' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $sede->departamento }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $sede->municipio }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $sede->estado }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $sede->calendario }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $sede->colegio_id }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-4">No hay sedes registradas para este colegio.</p>
        @endif
    </div>

    <div class="px-6 py-4 flex justify-end">
        <button wire:click="$set('modalSedes', false)"
            class="px-4 cursor-pointer py-2 bg-gray-600 text-white rounded hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-800">
            Cerrar
        </button>
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
    Livewire.on('confirmarEliminarColegio', (id) => {
            Swal.fire({
                title: "Estas Seguro?",
                text: "Esto no se puede deshacer!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, Eliminalo"
                }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('eliminarColegio', id);
                }
            });
        });
</script>
@endpush


</div>
