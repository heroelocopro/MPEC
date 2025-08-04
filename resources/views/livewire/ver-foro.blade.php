<div class="space-y-6">
    {{-- Breadcrumbs --}}
    <div class="mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('login') }}">Panel</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('foro') }}">Foro</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('ver-foro', $foro->id) }}">{{ $foro->titulo }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    {{-- Contenido del Foro --}}
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $foro->titulo }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Publicado el {{ $foro->created_at->format('d/m/Y H:i') }} por
                    <span class="font-medium text-gray-800 dark:text-gray-200">
                        {{ ucfirst($foro->autor->nombre ?? $foro->autor->nombre_completo ?? 'Usuario') }}
                    </span>
                </p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                {{ $foro->tipo }}
            </span>
        </div>

        <div class="text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-line">
            {{ $foro->contenido }}
        </div>
    </div>

    {{-- Formulario de Comentario --}}
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Escribe un comentario</h2>

        <form wire:submit.prevent="comentar">
            <textarea wire:model.defer="mensaje"
                      rows="4"
                      placeholder="Escribe tu comentario aquí..."
                      class="w-full px-4 py-2 text-sm text-gray-800 bg-gray-100 dark:bg-gray-800 dark:text-white rounded-md border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none">
            </textarea>

            @error('mensaje')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror

            <div class="mt-4 flex justify-end">
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition">
                    Comentar
                </button>
            </div>
        </form>
    </div>

    {{-- Comentarios --}}
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            Comentarios ({{ $foro->total_comentarios }})
        </h2>

        @if (isset($comentarios) && count($comentarios) > 0)
            <div class="space-y-4">
                @foreach ($comentarios as $comentario)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                               {{ $comentario->autor->nombre ?? $comentario->autor->nombre_completo ?? 'Usuario' }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $comentario->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            {{ $comentario->mensaje }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Aún no hay comentarios.</p>
        @endif
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
                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            });
        });
    </script>
    @endpush
</div>
