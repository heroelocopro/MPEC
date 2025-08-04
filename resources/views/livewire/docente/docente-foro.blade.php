<div>
    <!-- Encabezado y botón -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Foros del Docente</h2>
        <button wire:click="$toggle('modalCrear')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg dark:bg-blue-700 dark:hover:bg-blue-800">
            Crear Foro
        </button>
    </div>

    <!-- Filtros -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <select wire:model="filtroTipo"
                class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg p-2">
            <option value="">Todos los Tipos</option>
            <option value="Global">Global</option>
            <option value="Grado">Grado</option>
            <option value="Grupo">Grupo</option>
        </select>
        <input type="text" wire:model.debounce.300ms="busqueda"
               placeholder="Buscar por título..."
               class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg p-2"/>
    </div>

    <!-- Lista de foros -->
    <div class="grid gap-4">
        @forelse ($foros as $foro)
            <div class="p-4 border rounded-lg shadow-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $foro->titulo }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Tipo: {{ $foro->tipo }}</p>
                        <p class="mt-2 text-gray-700 dark:text-gray-300">{{ $foro->contenido }}</p>
                    </div>
                    <div class="text-sm text-right text-gray-500 dark:text-gray-400">
                        <p>Autor: {{ $foro->tipo_autor }}</p>
                        <p class="text-xs">{{ $foro->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500 dark:text-gray-400">No hay foros disponibles.</p>
        @endforelse
    </div>

    <!-- Modal Crear Foro -->
    @if($modalCrear)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-lg w-full max-w-xl z-50">
            <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Crear Nuevo Foro</h3>

            <form wire:submit.prevent="crearForo" class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Título</label>
                    <input type="text" wire:model.defer="titulo"
                           class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
                    @error('titulo')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Contenido</label>
                    <textarea wire:model.defer="contenido" rows="4"
                              class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea>
                    @error('contenido')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Tipo de Foro</label>
                    <select wire:model.defer="tipo"
                            class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="Global">Global</option>
                        <option value="Grado">Grado</option>
                        <option value="Grupo">Grupo</option>
                    </select>
                    @error('tipo')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" wire:click="$set('modalCrear', false)"
                            class="px-4 py-2 text-gray-700 dark:text-white bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-white bg-green-600 hover:bg-green-700 rounded">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
