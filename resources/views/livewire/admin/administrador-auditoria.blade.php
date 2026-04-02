<div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-screen text-gray-800 dark:text-gray-200">

    <!-- 🔍 FILTROS -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- Modelo -->
            <div>   
                <label class="text-sm font-semibold">Modelo</label>
                <select wire:model.live="modeloSeleccionado"
                    class="w-full mt-1 p-2 rounded-lg bg-gray-100 dark:bg-gray-700 border-none focus:ring-2 focus:ring-blue-500">
                    <option value="Todos">Todos</option>
                    <option value="App\Models\Estudiante">Estudiante</option>
                    <option value="App\Models\Matricula">Matricula</option>
                    <option value="App\Models\Colegio">Colegio</option>
                    <option value="App\Models\PeriodoAcademico">Periodo Académico</option>
                    <!-- agrega más modelos aquí -->
                </select>
            </div>

            <!-- Tipo de evento -->
            <div>
                <label class="text-sm font-semibold">Evento</label>
                <select wire:model.live="filtroEvento"
                    class="w-full mt-1 p-2 rounded-lg bg-gray-100 dark:bg-gray-700 border-none focus:ring-2 focus:ring-blue-500">
                    <option value="Todos">Todos</option>
                    <option value="created">Creado</option>
                    <option value="updated">Actualizado</option>
                    <option value="deleted">Eliminado</option>
                </select>
            </div>

            <!-- Buscador -->
            <div class="md:col-span-2">
                <label class="text-sm font-semibold">Buscar</label>
                <input type="text" wire:model.live="search"
                    placeholder="Buscar por ID, usuario..."
                    class="w-full mt-1 p-2 rounded-lg bg-gray-100 dark:bg-gray-700 border-none focus:ring-2 focus:ring-blue-500">
            </div>

        </div>
    </div>

    <!-- 📊 TABLA -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
              <tr class="select-none">

                    <!-- ID -->
                    <th wire:click="ordenar('auditable_id')" class="p-3 cursor-pointer">
                        <div class="flex items-center gap-1">
                            ID
                            @if($sortField === 'auditable_id')
                                @if($sortDirection === 'asc')
                                    ▲
                                @else
                                    ▼
                                @endif
                            @else
                                ⇅
                            @endif
                        </div>
                    </th>

                    <!-- Modelo -->
                    <th wire:click="ordenar('auditable_type')" class="p-3 cursor-pointer">
                        <div class="flex items-center gap-1">
                            Modelo
                            @if($sortField === 'auditable_type')
                                @if($sortDirection === 'asc') ▲ @else ▼ @endif
                            @else ⇅ @endif
                        </div>
                    </th>

                    <!-- Evento -->
                    <th wire:click="ordenar('event')" class="p-3 cursor-pointer">
                        <div class="flex items-center gap-1">
                            Evento
                            @if($sortField === 'event')
                                @if($sortDirection === 'asc') ▲ @else ▼ @endif
                            @else ⇅ @endif
                        </div>
                    </th>

                    <!-- Usuario -->
                    <th wire:click="ordenar('users.name')" class="p-3 cursor-pointer">
                        <div class="flex items-center gap-1">
                            Usuario
                            @if($sortField === 'users.name')
                                @if($sortDirection === 'asc') ▲ @else ▼ @endif
                            @else ⇅ @endif
                        </div>
                    </th>

                    <!-- Cambios (no ordenable realmente útil, pero opcional) -->
                    <th class="p-3">Cambios</th>

                    <!-- Fecha -->
                    <th wire:click="ordenar('audits.created_at')" class="p-3 cursor-pointer">
                        <div class="flex items-center gap-1">
                            Fecha
                            @if($sortField === 'audits.created_at')
                                @if($sortDirection === 'asc') ▲ @else ▼ @endif
                            @else ⇅ @endif
                        </div>
                    </th>

                </tr>
            </thead>

            <tbody>
                @forelse ($auditorias as $audit)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">

                        <!-- ID -->
                        <td class="p-3">
                            {{ $audit->auditable_id }}
                        </td>

                        <!-- Modelo -->
                        <td class="p-3">
                            {{ class_basename($audit->auditable_type) }}
                        </td>

                        <!-- Evento -->
                        <td class="p-3">
                            <span class="
                                px-2 py-1 rounded text-xs font-semibold
                                @if($audit->event === 'created') bg-green-200 text-green-800
                                @elseif($audit->event === 'updated') bg-yellow-200 text-yellow-800
                                @elseif($audit->event === 'deleted') bg-red-200 text-red-800
                                @endif
                            ">
                                {{ $audit->event }}
                            </span>
                        </td>

                        <!-- Usuario -->
                        <td class="p-3">
                            {{"#". $audit->user_id ." ". $audit->name ?? 'Sistema' }}
                        </td>

                        <!-- Cambios -->
                        <td class="p-3">
                            <div class="text-xs">
                                @if($audit->event === 'updated')
                                    <div>
                                        <strong>Antes:</strong>
                                        {{ json_encode($audit->old_values) }}
                                    </div>
                                    <div>
                                        <strong>Después:</strong>
                                        {{ json_encode($audit->new_values) }}
                                    </div>
                                @elseif($audit->event === 'created')
                                    <div>
                                        <strong>Creado:</strong>
                                        {{ json_encode($audit->new_values) }}
                                    </div>
                                @elseif($audit->event === 'deleted')
                                    <div>
                                        <strong>Eliminado:</strong>
                                        {{ json_encode($audit->old_values) }}
                                    </div>
                                @endif
                            </div>
                        </td>

                        <!-- Fecha -->
                        <td class="p-3">
                            {{ $audit->created_at }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">
                            No hay auditorías
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 📄 PAGINACIÓN -->
    @if($auditorias->hasPages())
    <div class="mt-4">
        {{ $auditorias->links() }}
    </div>
    @endif
</div>