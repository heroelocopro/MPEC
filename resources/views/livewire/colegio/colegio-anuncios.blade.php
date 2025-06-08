<div>
    {{-- parte superior --}}
    <div class="flex items-center justify-between mb-6">
        {{-- Migajas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('colegio-grados') }}">Anuncios</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
    </div>
    {{-- parte principal --}}
    <div class="p-6 max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold text-blue-800 dark:text-blue-300 mb-6">📢 Crear Anuncio del Colegio</h2>

        @if (session()->has('success'))
            <div class="mb-4 p-3 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-lg transition">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="guardar" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-8 space-y-4 transition">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Título</label>
                <input type="text" wire:model.defer="titulo"
                       class="w-full mt-1 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring focus:ring-blue-200 dark:focus:ring-blue-400 transition" />
                @error('titulo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Contenido</label>
                <textarea wire:model.defer="contenido" rows="4"
                          class="w-full mt-1 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring focus:ring-blue-200 dark:focus:ring-blue-400 transition"></textarea>
                @error('contenido') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Imagen (opcional)</label>
                <input type="file" wire:model.defer="imagen"
                       class="mt-1 text-gray-900 dark:text-gray-200" />
                @error('imagen') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                @if ($imagen)
                    <img src="{{ $imagen->temporaryUrl() }}" class="mt-4 w-32 h-32 object-cover rounded-lg ring-2 ring-blue-300 dark:ring-blue-500" alt="Vista previa">
                @endif
            </div>

            <button  type="submit"
                    class="bg-blue-600 cursor-pointer hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                Publicar Anuncio
            </button>
        </form>

        @if (!$mostrarAnuncios)
            <div class="mb-6 text-center">
                <button wire:click="cargarAnuncios"
                    class="bg-blue-600 cursor-pointer hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
                    📢 Mostrar Anuncios
                </button>
            </div>
        @endif


        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">📃 Anuncios Publicados</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($anuncios as $anuncio)
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-md flex flex-col justify-between h-full">
                <div>
                    @if($anuncio->imagen)
                        <img src="{{ asset('storage/'.$anuncio->imagen) }}" class="w-full h-40 object-cover rounded-lg mb-3 ring-1 ring-gray-200 dark:ring-gray-700" />
                    @endif

                    <h4 class="text-blue-700 dark:text-blue-300 font-semibold">{{ $anuncio->titulo }}</h4>
                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-2">{{ $anuncio->contenido }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Publicado: {{ $anuncio->created_at->format('d M Y') }}</p>
                </div>

                <div class="flex justify-end mt-4 space-x-2">
                    <button wire:click="editar({{ $anuncio->id }})"
                        class="text-blue-600 cursor-pointer dark:text-blue-400 hover:underline text-sm font-medium">
                        ✏️ Editar
                    </button>
                    <button wire:click="$dispatch('confirmarEliminarAnuncio', {id:{{ $anuncio->id }} })"
                        class="text-red-600 cursor-pointer dark:text-red-400 hover:underline text-sm font-medium">
                        🗑️ Eliminar
                    </button>

                </div>
            </div>


            @empty
                <div class="col-span-3 text-center text-gray-500 dark:text-gray-400 italic">
                    No hay anuncios disponibles.
                </div>
            @endforelse
        </div>
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
</script>
    @endpush


</div>
