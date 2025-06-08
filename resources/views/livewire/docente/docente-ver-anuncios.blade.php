<div>
    {{-- parte superior --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('docente-anuncios') }}">Anuncios</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="">Ver</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="">{{ $colegio->nombre }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
        <a href="{{ route('docente-anuncios') }}"
        class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition">
            Volver
        </a>
    </div>
     {{-- Contenido principal --}}
     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($anuncios as $anuncio)
            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg shadow p-4">
                @if ($anuncio->imagen)
                    <img src="{{ asset('storage/' . $anuncio->imagen) }}"
                         alt="Imagen del anuncio"
                         class="w-full h-48 object-cover rounded mb-4">
                @endif

                <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">{{ $anuncio->titulo }}</h2>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ Str::limit($anuncio->contenido, 120) }}</p>

                <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                    Publicado el {{ $anuncio->created_at->format('d M Y') }}
                </div>
                <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                    Publicado por {{ $anuncio->anunciable->nombre_completo }}
                </div>
                {{-- Botones --}}
                <div class="mt-4 flex justify-between gap-2">
                    <a wire:click="cargarAnuncio({{ $anuncio->id }})"
                       class="flex-1 cursor-pointer inline-block text-center px-4 py-2 bg-yellow-500 text-white text-sm rounded-lg hover:bg-yellow-600 transition">
                        Editar
                    </a>

                    <button wire:click="$dispatch('confirmarEliminarAnuncio', {id:  {{ $anuncio->id }}})"
                            class="flex-1 cursor-pointer px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                        Eliminar
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-500 dark:text-gray-400">
                No hay anuncios registrados aún.
            </div>
        @endforelse
    </div>

    {{-- Modal Editar Anuncio --}}
    <flux:modal wire:model="editarModal" name="editar-anuncio" class="md:w-[700px]">
        <form wire:submit.prevent="editar" class="space-y-6">
            <div>
                <flux:heading size="lg">Editar anuncio</flux:heading>
                <flux:text class="mt-2">Modifica la información del anuncio y guarda los cambios.</flux:text>
            </div>

            {{-- Título --}}
            <flux:input wire:model.defer="titulo" label="Título" placeholder="Título del anuncio" />
            @error('titulo') <span class="text-sm text-red-500">{{ $message }}</span> @enderror

            {{-- Contenido --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-200 text-sm font-medium mb-1">Contenido</label>
                <textarea wire:model.defer="contenido"
                        class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-800 dark:text-gray-200"
                        rows="4"
                        placeholder="Contenido del anuncio"></textarea>
                @error('contenido') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            {{-- Imagen --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-200 text-sm font-medium mb-2">Imagen</label>
                <input wire:model="imagenNueva" type="file"
                    class="w-full ps-5 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4
                            file:rounded-lg file:border-0 file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100
                            dark:file:bg-gray-700 dark:file:text-white dark:hover:file:bg-gray-600"
                    accept="image/*">
                @error('imagenNueva') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                {{-- Cargando imagen --}}
                <div wire:loading wire:target="imagenNueva" class="text-blue-600 dark:text-blue-400 text-sm mt-2">
                    Subiendo imagen...
                </div>

                {{-- Vista previa --}}
                @if ($imagenNueva)
                    <div class="mt-4 flex justify-center">
                        <img src="{{ $imagenNueva->temporaryUrl() }}" alt="Vista previa"
                            class="max-h-64 rounded-lg border border-gray-300 dark:border-gray-700">
                    </div>
                @elseif ($imagenVieja)
                    <div class="mt-4 flex justify-center">
                        <img src="{{ asset('storage/' . $imagenVieja) }}" alt="Imagen actual"
                            class="max-h-64 rounded-lg border border-gray-300 dark:border-gray-700">
                    </div>
                @endif
            </div>

            {{-- Botones --}}
            <div class="flex justify-end gap-2 pt-2">
                <flux:button class="cursor-pointer" wire:click="$set('editarModal',false)" type="button">Cancelar</flux:button>
                <flux:button wire:click="editar" class="cursor-pointer" >Guardar cambios</flux:button>
            </div>
        </form>
    </flux:modal>


    {{-- alertas --}}
    {{-- JavaScript --}}
    @push('js')
    <script>
        Livewire.on('confirmarEliminarAnuncio', (id) => {
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
                Livewire.dispatch('eliminarAnuncio', id);
            }
        });
    });
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



</div>
