<div>
    {{-- Notificaciones fijas a la derecha superior --}}
    <div class="fixed top-4 right-4 z-50 cursor-pointer">
        <livewire:notificaciones />
    </div>
    {{-- Parte Superior --}}
    <div class="flex items-center justify-between mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('estudiante-anuncios') }}">Anuncios</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ $grupo->nombre }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    {{-- Contenido dividido en 2 columnas --}}
    <div class="px-6 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Columna de Anuncios del Colegio --}}
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white mb-4">🏫 Anuncios del Colegio</h1>
                <div class="space-y-6">
                    @forelse($anuncios->where('anunciable_type', 'App\Models\Colegio') as $anuncio)
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-md p-5 flex flex-col">
                            @if($anuncio->imagen)
                                <img src="{{ asset('storage/'.$anuncio->imagen) }}" alt="Imagen del anuncio" class="w-full h-40 object-cover rounded-lg mb-4">
                            @endif
                            <h2 class="text-lg font-semibold text-blue-700 dark:text-blue-300 mb-2">{{ $anuncio->titulo }}</h2>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4 line-clamp-3">
                                {{ $anuncio->contenido }}
                            </p>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-auto">
                                Publicado el {{ \Carbon\Carbon::parse($anuncio->created_at)->format('d M Y') }}
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 italic">No hay anuncios del colegio disponibles.</p>
                    @endforelse
                </div>
            </div>

            {{-- Columna de Anuncios de Docentes --}}
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white mb-4">👨‍🏫 Anuncios de Docentes</h1>
                <div class="space-y-6">
                    @forelse($anuncios->where('anunciable_type', 'App\Models\Profesor') as $anuncio)
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-md p-5 flex flex-col">
                            @if($anuncio->imagen)
                                <img src="{{ asset('storage/'.$anuncio->imagen) }}" alt="Imagen del anuncio" class="w-full h-40 object-cover rounded-lg mb-4">
                            @endif
                            <h2 class="text-lg font-semibold text-blue-700 dark:text-blue-300 mb-2">{{ $anuncio->titulo }}</h2>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4 line-clamp-3">
                                {{ $anuncio->contenido }}
                            </p>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-auto">
                                Publicado el {{ \Carbon\Carbon::parse($anuncio->created_at)->format('d M Y') }}
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 italic">No hay anuncios de docentes disponibles.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
