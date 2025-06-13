<div class="">
    {{-- Parte Superior --}}
    {{-- Notificaciones fijas a la derecha superior --}}
    <div class="fixed top-4 right-4 z-50 cursor-pointer">
        <livewire:notificaciones />
    </div>
    <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
        {{-- Migajas de pan --}}
        <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#" class="hover:underline">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('estudiante-actividades') }}" class="hover:underline">Actividades</flux:breadcrumbs.item>
                @if(isset($colegio))
                <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                @endif
                @if(isset($grupo))

                <flux:breadcrumbs.item>{{ $grupo->nombre }}</flux:breadcrumbs.item>
                @endif
            </flux:breadcrumbs>
        </div>

        <h1 class="text-2xl font-extrabold text-blue-900 dark:text-blue-300">
            Actividades por Asignatura
        </h1>
    </div>

    {{-- Grid de asignaturas con actividades --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        @php
            $iconos = [
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
            ];
        @endphp
        @if (isset($actividadesAsignaturas) && count($actividadesAsignaturas) > 0)
        @foreach ($actividadesAsignaturas as $index => $asignatura)
            <div class="relative rounded-3xl border-2 border-blue-300 bg-white dark:bg-blue-900 p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                {{-- Icono grande difuminado atrás --}}
                <div class="absolute top-6 right-6 text-8xl opacity-10 select-none pointer-events-none">
                    {{ $iconos[$asignatura[0]] ?? '📘' }}
                </div>

                {{-- Nombre asignatura --}}
                <h3 class="text-2xl font-bold text-blue-900 dark:text-blue-300 mb-6 flex items-center gap-3">
                    <span class="text-4xl">{{ $iconos[$asignatura[0]] ?? '📘' }}</span> {{ $asignatura[0] }}
                </h3>

                {{-- Lista de actividades --}}
                @if (empty($asignatura[1]) || count($asignatura[1]) === 0)
                    <p class="text-lg font-medium text-gray-600 dark:text-gray-400 italic">
                        ¡Aún no hay actividades para esta materia!
                    </p>
                @else
                    <ul class="space-y-5 max-h-72 overflow-y-auto pr-2">
                        @foreach ($asignatura[1] as $actividad)
                            <li class="bg-blue-50 dark:bg-blue-800 border border-blue-200 dark:border-blue-700 rounded-xl p-4 shadow hover:scale-[1.03] transform transition-transform duration-200 cursor-pointer">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-semibold text-blue-800 dark:text-blue-200 text-lg leading-snug">
                                            📝 {{ $actividad->titulo }}
                                        </h4>
                                        <p class="text-sm text-blue-600 dark:text-blue-300 mt-1">
                                            Entrega: <time datetime="{{ $actividad->fecha_entrega }}">{{ \Carbon\Carbon::parse($actividad->fecha_entrega)->format('d/m/Y') }}</time>
                                        </p>
                                    </div>
                                    <div class="ml-3 shrink-0">
                                        <flux:modal.trigger wire:click="cargarSubirActividad({{ $actividad->id }})" name="subir-actividad">
                                            <flux:button class="bg-blue-600 cursor-pointer hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm font-medium transition">
                                                subir actividad
                                            </flux:button>
                                        </flux:modal.trigger>
                                        <flux:modal.trigger wire:click="cargarActividad({{ $actividad->id }})" name="ver-actividad">
                                            <flux:button class="bg-blue-600 cursor-pointer hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm font-medium transition">
                                                Ver
                                            </flux:button>
                                        </flux:modal.trigger>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
        @endif
    </div>

    {{-- Modal para ver la actividad --}}
    <flux:modal wire:model.live="verModal" name="ver-actividad" class="lg:w-full max-w-2xl rounded-xl shadow-xl bg-white dark:bg-blue-900 p-6">
        <div class="space-y-6">
            {{-- Encabezado --}}
            <div>
                <flux:heading size="lg" class="text-blue-900 dark:text-blue-300 font-extrabold">Detalle de la Actividad</flux:heading>
                <flux:text class="mt-2 text-gray-700 dark:text-gray-300">
                    Aquí puedes ver la información completa de la actividad seleccionada.
                </flux:text>
            </div>

            {{-- Cargando --}}
            @if ($cargandoActividad)
                <div class="text-center text-blue-500 dark:text-blue-300 py-10">
                    <svg class="animate-spin h-8 w-8 mx-auto mb-3 text-blue-600 dark:text-blue-400" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                    </svg>
                    Cargando información de la actividad...
                </div>
            @elseif (!empty($actividadModal))
                {{-- Contenido --}}
                <div class="bg-blue-50 dark:bg-blue-800 border border-blue-300 dark:border-blue-700 rounded-xl p-6 space-y-5">
                    <div>
                        <p class="text-sm font-semibold text-gray-700 dark:text-white">Título:</p>
                        <p class="text-lg text-blue-900 dark:text-blue-100 font-semibold">{{ $actividadModal->titulo }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-700 dark:text-white">Descripción:</p>
                        <p class="text-base text-blue-900 dark:text-blue-100 whitespace-pre-line leading-relaxed">
                            {{ $actividadModal->descripcion }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-white">Fecha de Entrega:</p>
                            <p class="text-base text-blue-900 dark:text-blue-100 font-medium">
                                {{ \Carbon\Carbon::parse($actividadModal->fecha_entrega)->format('d/m/Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-white">Asignatura:</p>
                            <p class="text-base text-blue-900 dark:text-blue-100 font-medium">{{ $actividadModal->asignatura->nombre ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if ($actividadModal->archivo)
                        <div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-white">Archivo Adjunto:</p>
                            <a href="{{ asset('storage/' . $actividadModal->archivo) }}" target="_blank" class="inline-block mt-1 text-blue-700 hover:underline dark:text-blue-400 font-medium">
                                Descargar archivo 📎
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-400 text-center italic">No se ha seleccionado ninguna actividad.</p>
            @endif

            {{-- Botón cerrar --}}
            <div class="flex justify-end">
                <flux:button variant="primary" wire:click="$set('verModal', false)" class="px-6 cursor-pointer py-2">
                    Cerrar
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal wire:model.live="verModalActividad" name="subir-actividad" class="lg:w-full max-w-md rounded-xl shadow-xl bg-white dark:bg-blue-900 p-6">
        <div class="space-y-6">
            {{-- Encabezado --}}
            <div>
                <flux:heading size="lg" class="text-blue-900 dark:text-blue-300 font-extrabold">
                    Subir respuesta de la actividad
                </flux:heading>
                <flux:text class="mt-2 text-gray-700 dark:text-gray-300">
                    Completa el contenido y adjunta el archivo si es necesario.
                </flux:text>
            </div>

            {{-- Mostrar spinner mientras carga --}}
            @if ($cargandoSubirActividad)
                <div class="text-center text-blue-500 dark:text-blue-300 py-10">
                    <svg class="animate-spin h-8 w-8 mx-auto mb-3 text-blue-600 dark:text-blue-400" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                    </svg>
                    Cargando datos...
                </div>
            @else
                {{-- Formulario --}}
                <form wire:submit.prevent="guardarRespuesta" enctype="multipart/form-data" class="space-y-6">
                    {{-- Contenido --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Contenido</label>
                        <textarea wire:model.defer="contenido" rows="5" class="input-form dark:bg-gray-700 dark:text-white" placeholder="Escribe aquí tu respuesta o contenido..."></textarea>
                        @error('contenido') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Archivo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Archivo adjunto (opcional)</label>
                        <input type="file" wire:model="archivo" class="text-gray-900 dark:text-white">
                        @error('archivo') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

                        @if ($archivo)
                            <p class="mt-1 text-sm text-green-600 dark:text-green-400">Archivo listo para subir: {{ $archivo->getClientOriginalName() }}</p>
                        @elseif (!empty($archivoGuardado))
                            <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">Archivo actual: {{ basename($archivoGuardado) }}</p>
                            <a target="_blank" href="{{ asset('storage/'.$archivoGuardado) }}">descargar</a>
                        @endif
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end space-x-3">
                        <flux:button type="button" wire:click="$set('verModalActividad', false)" class="px-6 py-2 cursor-pointer">
                            Cancelar
                        </flux:button>
                        <flux:button type="submit" class="px-6 py-2 cursor-pointer">
                            Guardar
                        </flux:button>
                    </div>
                </form>
            @endif

            {{-- Cargando archivo o guardar --}}
            <div wire:loading wire:target="guardarRespuesta,cargarSubirActividad, archivo" class="text-center text-blue-500 dark:text-blue-300 py-4">
                <svg class="animate-spin h-8 w-8 mx-auto mb-3 text-blue-600 dark:text-blue-400" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                </svg>
                Procesando...
            </div>
        </div>
    </flux:modal>


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
</script>
@endpush

</div>
