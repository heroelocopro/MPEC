<div>
    {{-- Encabezado superior --}}
    <div class="flex items-center justify-between mb-6">
        {{-- Migas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="">Configuracion</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('colegio-notas') }}">Notas </flux:breadcrumbs.item>
                @isset($colegio)
                    <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                @endisset
            </flux:breadcrumbs>
        </div>
    </div>
    {{-- principal --}}

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 ring-1 ring-blue-200 dark:ring-blue-700">
        <h2 class="text-2xl font-bold text-blue-700 dark:text-blue-300 mb-4 text-center">
            🎯 Configuración de Escala de Notas
        </h2>

        <form wire:submit.prevent="guardarConfiguracion" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Nota Mínima --}}
                <div>
                    <label for="nota_minima" class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-1">
                        Nota Mínima
                    </label>
                    <input type="number" step="0.01" min="0" max="10" wire:model.defer="nota_minima"
                        id="nota_minima"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 px-4 py-2 transition">
                </div>

                {{-- Nota Máxima --}}
                <div>
                    <label for="nota_maxima" class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-1">
                        Nota Máxima
                    </label>
                    <input type="number" step="0.01" min="0" max="10" wire:model.defer="nota_maxima"
                        id="nota_maxima"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 px-4 py-2 transition">
                </div>

                {{-- Nota para Aprobar --}}
                <div>
                    <label for="nota_aprobacion" class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-1">
                        Nota para Aprobar
                    </label>
                    <input type="number" step="0.01" min="0" max="10" wire:model.defer="nota_requerida"
                        id="nota_requerida"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 px-4 py-2 transition">
                </div>
            </div>

            <div class="text-center mt-6">
                <button type="submit"
                    class="inline-flex cursor-pointer items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-200">
                    💾 Guardar Configuración
                </button>
            </div>
        </form>
    </div>

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
