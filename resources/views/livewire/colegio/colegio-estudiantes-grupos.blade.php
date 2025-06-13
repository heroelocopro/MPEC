<div>
    <div class="flex items-center justify-between mb-6">
        {{-- Migajas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('colegio-grados') }}">Estudiante - Grupo</flux:breadcrumbs.item>
                @isset($colegio)
                    <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                @endisset
            </flux:breadcrumbs>
        </div>

        {{-- Botón para asignar estudiante a grupo --}}
        <div>
            @if ($grupoInfo)
                <flux:modal.trigger wire:click="$set('modalCreacion', true)" name="crear-profesor">
                    <button
                        class="h-12 px-6 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition duration-300 cursor-pointer dark:bg-blue-700 dark:hover:bg-blue-800">
                        Asignar Estudiante a Grupo
                    </button>
                </flux:modal.trigger>
            @endif
        </div>
    </div>

    {{-- Título --}}
<h2 class="text-center text-lg font-semibold text-gray-800 dark:text-white mb-4">Selecciona un Grupo</h2>

{{-- Grid de tarjetas --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4">

    @forelse($grupos as $grupo)
        <div
            wire:click="$set('grupo_id', {{ $grupo->id }})"
            class="cursor-pointer p-4 border rounded-xl shadow-sm transition hover:shadow-md
                {{ $grupo_id == $grupo->id ? 'border-blue-500 bg-blue-100 dark:bg-blue-800 dark:border-blue-400 text-blue-800 dark:text-white' : 'bg-white dark:bg-neutral-800 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white dark:hover:bg-blue-700' }}"
        >
            <div class="text-lg font-semibold">{{ $grupo->nombre }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300">Grado: {{ $grupo->grado->nombre }}</div>
        </div>
    @empty
        <div class="col-span-full text-center text-gray-500 dark:text-gray-400">
            No hay grupos disponibles.
        </div>
    @endforelse

</div>

    {{-- Mostrar estudiantes asignados al grupo --}}
@if ($grupoInfo)
<div class="max-w-6xl mx-auto mt-10 p-6 rounded-2xl shadow-lg bg-white dark:bg-gray-800">
    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Información del Grupo</h3>
        <p class="text-gray-600 dark:text-gray-300"><strong>Nombre:</strong> {{ $grupoInfo->nombre }}</p>
        <p class="text-gray-600 dark:text-gray-300"><strong>Grado:</strong> {{ $grupoInfo->grado->nombre }}</p>
    </div>

    <div class="mt-6">
        <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Estudiantes</h4>

        @if ($estudiantesGrupos->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($estudiantesGrupos as $item)
                    <div
                        class="relative p-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 flex flex-col gap-2 text-gray-900 dark:text-gray-100">
                        <p>{{ $item->estudiante->nombre_completo }}</p>

                        <!-- Botón Eliminar -->
                        <button wire:click="$dispatch('confirmarEliminarEstudianteGrupo', { id: {{ $item->id }} })"
                            class="mt-auto text-sm bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-700 dark:text-red-100 dark:hover:bg-red-600 px-3 py-1 rounded-md cursor-pointer transition">
                            Eliminar
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-700 dark:text-gray-300">No hay Estudiantes en ese grupo</p>
        @endif
    </div>
</div>
@endif


    {{-- Modal para asignar estudiante a grupo --}}
    <flux:modal name="crear-grado" wire:model="modalCreacion" class="md:w-96 lg:w-10/12">
        <div class="space-y-6">
            {{-- Título Modal --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Asignación del Estudiante al Grupo</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Asigne toda la información requerida.</p>
            </div>

            {{-- Errores --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 dark:bg-red-800/30">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500 dark:text-red-300" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Hay {{ $errors->count() }} error(es) en el formulario
                            </h3>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Información Básica --}}
            <div class="space-y-4">
                {{-- Grupo Actual --}}
                <div>
                    <label for="grupo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Grupo</label>
                    <span class="text-red-500">{{ $grupoInfo?->nombre ?? 'No seleccionado' }}</span>
                </div>

                {{-- Estudiante --}}
                <div>
                    <label for="estudiante_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Estudiante</label>
                    <select id="estudiante_id" wire:model.live="estudiante_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        @if (count($estudiantes) > 0)
                        <option selected value="">Selecciona un estudiante</option>
                        @endif
                        @forelse ($estudiantes as $estudiante)
                            <option value="{{ $estudiante->id }}">{{ $estudiante->nombre_completo }}</option>
                        @empty
                            <option value="">No hay estudiantes disponibles</option>
                        @endforelse
                    </select>
                    @error('estudiante_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Footer modal --}}
            <div class="flex pt-4">
                <div class="flex-1"></div>
                <div class="flex space-x-3">
                    <button type="button" wire:click="asignarEstudianteGrupo"
                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 cursor-pointer">
                        Asignar Estudiante
                    </button>
                </div>
            </div>
        </div>
    </flux:modal>

    {{-- Script para alertas --}}
    @push('js')
        <script>
            Livewire.on('alerta', (data) => {
                data = data[0];
                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.icon,
                    toast: data.toast || false,
                    position: data.position || 'center',
                });
            });
            Livewire.on('confirmarEliminarEstudianteGrupo', (id) => {
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
                    Livewire.dispatch('eliminarEstudianteGrupo', id);
                }
            });
        });
        </script>
    @endpush
</div>
