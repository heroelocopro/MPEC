<div>
    {{-- parte superior --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('docente-anuncios') }}">Anuncios</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="">Crear</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="">{{ $colegio->nombre }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
        <a href="{{ route('docente-ver-anuncios') }}"
        class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition">
            Ver Anuncios
        </a>
    </div>
    {{-- parte principal --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-md p-6 md:p-8 max-w-3xl mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Crear Nuevo Anuncio</h2>

        <form wire:submit.prevent="crearAnuncio" class="space-y-6">
            {{-- Título --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-200 text-sm font-medium mb-2">Título</label>
                <input wire:model.defer="titulo" type="text" class="w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Ingrese el título del anuncio">
                @error('titulo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Contenido --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-200 text-sm font-medium mb-2">Contenido</label>
                <textarea wire:model.defer="contenido" rows="5" class="w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Escriba el contenido del anuncio"></textarea>
                @error('contenido') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Imagen --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-200 text-sm font-medium mb-2">Imagen</label>

                {{-- Input de archivo con wire:model --}}
                <input wire:model="imagen" type="file"
                    class="w-full ps-5 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-white dark:hover:file:bg-gray-600"
                    accept="image/*">

                {{-- Mensaje de error --}}
                @error('imagen') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                {{-- Cargando imagen --}}
                <div wire:loading wire:target="imagen" class="text-blue-600 dark:text-blue-400 text-sm mt-2">
                    Subiendo imagen...
                </div>

                {{-- Vista previa --}}
                @if ($imagen)
                    <div class="mt-4 flex flex-col items-center">
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">Vista previa:</p>
                        <img src="{{ $imagen->temporaryUrl() }}" alt="Vista previa"
                            class="max-h-64 rounded-lg border border-gray-300 dark:border-gray-700 mx-auto shadow-md">
                    </div>
                @endif
            </div>


            {{-- Botón --}}
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition duration-200">
                    Guardar Anuncio
                </button>
            </div>
        </form>
    </div>


    {{-- modal --}}

    {{-- notificaciones --}}
        {{-- JavaScript --}}
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
</div>
