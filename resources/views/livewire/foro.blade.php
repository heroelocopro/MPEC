<div>
    {{-- Breadcrumbs --}}
    <div class="mb-6">
        <div class="flex justify-between items-center">
            {{-- Breadcrumbs alineado a la izquierda --}}
            <div>
                <flux:breadcrumbs>
                    <flux:breadcrumbs.item href="{{ route('login') }}">Panel</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>{{ $colegio->nombre ?? 'sin Colegio' }}</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item href="{{ route('foro') }}">Foro</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>

            {{-- Botón alineado a la derecha si el rol lo permite --}}
            @if ($usuario != null)

            @if ($usuario->usuario->role_id < 4)
            <div>
                <flux:button variant="primary" color="sky" class="h-12  px-6 bg-blue-600 text-white rounded-lg text-sm
                hover:bg-blue-700 transition duration-300 cursor-pointer
                dark:bg-blue-700 dark:hover:bg-blue-800" wire:click="$set('modalCrear',true)">Crear Foro</flux:button>
            </div>
            @endif
            @endif
        </div>
    </div>

    {{-- Contenido principal --}}
    <div class="space-y-6">

        {{-- Filtros --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex gap-4">
                <select wire:model.live="filtroTipo"
                    class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg ps-5 py-2">
                    <option value="">Todos los Tipos</option>
                    <option value="Global">Global</option>
                    <option value="Grado">Grado</option>
                    <option value="Grupo">Grupo</option>
                </select>

                <select wire:model.live="orden"
                    class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg ps-5 py-2">
                    <option value="desc">Más recientes</option>
                    <option value="asc">Más antiguos</option>
                </select>

                <select wire:model.live="paginate"
                    class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg ps-5 py-2">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                </select>
            </div>

            <input type="text" wire:model.live="busqueda"
                placeholder="Buscar por título..."
                class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg px-3 py-2 w-full sm:w-1/3" />
        </div>

        {{-- Lista de foros --}}
        <div class="space-y-4">
            @if (isset($foros) && count($foros) > 0)
                @foreach($foros as $foro)
                    <a href="{{ route('ver-foro',$foro->id) }}"
                        class="block  border rounded-lg p-4 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition text-gray-800 dark:text-gray-100">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-xl font-semibold">{{ $foro->titulo }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Publicado el {{ $foro->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span
                                    class="px-2 py-1 text-sm rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    @switch($foro->tipo)
                                        @case('Grupo')
                                            {{ $foro->tipo . ' ' . $foro->grupo->nombre }}
                                            @break
                                        @case('Grado')
                                            {{ $foro->tipo . ' ' . $foro->grado->nombre }}
                                            @break

                                        @default
                                            {{ $foro->tipo }}
                                    @endswitch

                                </span>
                            </div>
                        </div>

                        <p class="text-gray-700 dark:text-gray-300 line-clamp-2 mb-2">
                            {{ \Illuminate\Support\Str::limit($foro->contenido, 120) }}
                        </p>

                        <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
                            <span>Autor: {{ ucfirst($foro->autor->nombre == null ? $foro->autor->nombre_completo : $foro->autor->nombre) }}</span>
                            <span>{{ $foro->comentarios_count }} comentarios</span>
                        </div>
                    </a>
                    @endforeach
                    <div class="mt-4">
                        {{ $foros->links() }}
                    </div>
            @else
                <p class="text-center text-gray-500 dark:text-gray-400">No se encontraron foros.</p>
            @endif
        </div>

    </div> {{-- Cierra contenido principal --}}

{{-- Modal Crear Foro --}}
@if ($modalCrear)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-50">
    <div class="w-full max-w-2xl rounded-xl bg-white dark:bg-gray-900 shadow-xl p-6">
        {{-- Encabezado --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Crear Nuevo Foro</h2>
            <button wire:click="$set('modalCrear', false)" class="text-gray-500 hover:text-red-600 text-xl">&times;</button>
        </div>

        <form wire:submit.prevent="crearForo" class="space-y-4">
            {{-- Título --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300">Título</label>
                <input type="text" wire:model.defer="titulo"
                    class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                    required>
                @error('titulo') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            {{-- Contenido --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300">Contenido</label>
                <textarea wire:model.defer="contenido" rows="5"
                    class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                    required></textarea>
                @error('contenido') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            {{-- Tipo --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300">Tipo de Foro</label>
                <select wire:model.defer="tipo"
                    class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                    required>
                    <option value="">Selecciona un tipo</option>
                    <option value="Global">Global</option>
                    <option value="Grado">Grado</option>
                    <option value="Grupo">Grupo</option>
                </select>
                @error('tipo') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            {{-- Grado y Grupo --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Grado (opcional)</label>
                    <select wire:model.live="grado_id"
                        class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="">Selecciona grado</option>
                        @foreach ($grados as $grado)
                            <option value="{{ $grado->id }}">{{ $grado->nombre }}</option>
                        @endforeach
                    </select>
                    @error('grado_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Grupo (opcional)</label>
                    <select wire:model.defer="grupo_id"
                        class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="">Selecciona grupo</option>
                        @if (isset($grupos) && count($grupos) > 0)
                        {{-- @foreach gruposgrados->where('id', $grado_id)->first()?->grupos ?? [] as $grupo) --}}
                        @foreach ($grupos as $grupo)
                        <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                        @endforeach
                        @else
                        <option value="">No hay Grupos.</option>
                        @endif
                    </select>
                    @error('grupo_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex justify-end gap-4 pt-4">
                <button type="button" wire:click="$set('modalCrear', false)"
                    class="cursor-pointer px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                    Cancelar
                </button>
                <button type="submit"
                    class="cursor-pointer px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Crear Foro
                </button>
            </div>
        </form>
    </div>
</div>
@endif

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
                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            });
        });
    </script>
    @endpush

</div> {{-- Cierra todo el contenedor --}}
