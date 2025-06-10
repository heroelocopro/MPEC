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
            <select wire:model.live="pagination" class="w-full border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-100">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        {{-- Búsqueda --}}
        <div class="w-full md:w-[80%]">
            <input type="text" wire:model.live.debounce.500ms="search" placeholder="Buscar colegio..."
                class="w-full border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-100"
            />
        </div>

        {{-- Botón crear --}}
        <div class="w-full md:w-[10%]">
            <button wire:click="crearColegio"
                class="w-full bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700">
                Crear Colegio
            </button>
        </div>
    </div>

    {{-- Tabla --}}
    <div>
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
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 cursor-pointer"
                            wire:click="sortBy('{{ $campo }}')">
                            {{ $label }}
                            @if($sortField === $campo)
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                            @endif
                        </th>
                    @endforeach

                    <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Sedes</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                @forelse ($colegios as $colegio)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $colegio->id }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $colegio->nombre }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $colegio->codigo_dane }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $colegio->direccion }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $colegio->telefono }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $colegio->usuario->email }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $colegio->departamento }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $colegio->municipio }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $colegio->estado }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $colegio->calendario }}</td>

                        <td class="px-4 py-3">
                            <button wire:click="mostrarSedes({{ $colegio->id }})"
                                class="bg-purple-500 text-white px-3 py-1 rounded hover:bg-purple-600 text-xs dark:bg-purple-600 dark:hover:bg-purple-700 cursor-pointer">
                                Ver Sedes
                            </button>
                        </td>

                        <td class="px-4 py-3 space-x-2">
                            <button wire:click="editarColegio({{ $colegio->id }})"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs dark:bg-blue-600 dark:hover:bg-blue-700 cursor-pointer">
                                Editar
                            </button>
                            <button wire:click="confirmarEliminarColegio({{ $colegio->id }})"
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

    {{-- modal creacion del colegio --}}

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



</div>
