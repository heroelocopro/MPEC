<div>
    {{-- Notificaciones fijas a la derecha superior --}}
    <div class="fixed top-4 right-4 z-50 cursor-pointer">
        <livewire:notificaciones />
    </div>

    @php
        $iconosEspecialidad = [
            'Matemáticas' => '📐',
            'Lenguaje' => '📖',
            'Ciencias' => '🔬',
            'Arte' => '🎨',
            'Música' => '🎵',
            'Inglés' => '🇬🇧',
            'Educación Física' => '🏃',
            'Historia' => '📜',
            'Geografía' => '🌍',
            'Informática' => '💻',
            'Religión' => '🙏',
            'General' => '👨‍🏫',
        ];
    @endphp

    {{-- Parte Superior --}}
    <div class="flex items-center justify-between mb-6">
        {{-- Migas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('estudiante-docentes') }}">Docentes</flux:breadcrumbs.item>

                @isset($colegio)
                    <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                @endisset

                @isset($grupo)
                    <flux:breadcrumbs.item>{{ $grupo->nombre }}</flux:breadcrumbs.item>
                @endisset
            </flux:breadcrumbs>
        </div>
    </div>

    {{-- Parte Principal --}}
    <div class="px-6 py-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Nuestros Profesores</h1>

        @if(!empty($profesores) && count($profesores))
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($profesores as $profesor)
                    @php
                        $especialidad = $profesor->titulo_academico ?? 'General';
                        $emoji = $iconosEspecialidad[$especialidad] ?? '👨‍🏫';
                        $nombreCompleto = $profesor->nombre_completo ?? 'Nombre no disponible';
                        $asignaturas = $profesor->asignaturas ?? collect();
                        $fotoRuta = $profesor->foto ? asset('storage/'.$profesor->foto) : null;
                    @endphp

                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow hover:shadow-md transition p-5 flex flex-col items-center text-center">
                        {{-- Avatar o Emoji --}}
                        @if($fotoRuta)
                            <img src="{{ $fotoRuta }}" alt="{{ $nombreCompleto }}" class="w-20 h-20 rounded-full object-cover mb-3">
                        @else
                            <div class="w-20 h-20 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 flex items-center justify-center text-3xl font-bold mb-3">
                                {{ $emoji }}
                            </div>
                        @endif

                        {{-- Nombre y especialidad --}}
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $nombreCompleto }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">{{ $especialidad }}</p>

                        {{-- Asignaturas --}}
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                            @if($asignaturas->isNotEmpty())
                                <p class="font-semibold mb-1">Asignaturas:</p>
                                <ul class="list-disc list-inside">
                                    @foreach($asignaturas->take(3) as $asignatura)
                                        <li>{{ $asignatura->nombre ?? 'N/A' }}</li>
                                    @endforeach
                                    @if($asignaturas->count() > 3)
                                        <li>y más...</li>
                                    @endif
                                </ul>
                            @else
                                <p>No tiene asignaturas asignadas</p>
                            @endif
                        </div>

                        {{-- Botón opcional --}}
                        {{-- <a href="#" class="mt-auto text-sm bg-blue-500 hover:bg-blue-600 text-white px-4 py-1.5 rounded-full transition">Ver perfil</a> --}}
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600 dark:text-gray-400 italic">No se encontraron profesores asignados.</p>
        @endif
    </div>
</div>
