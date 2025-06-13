<div>
    {{-- Parte superior flex top --}}
     <div class="flex items-center justify-between mb-6">

        {{-- Migajas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('colegio-grados') }}">Docente - Asignaturas</flux:breadcrumbs.item>
                @isset($colegio)
                    <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                @endisset
            </flux:breadcrumbs>
        </div>

        {{-- Botón Crear Grupo --}}
        <div>
            @if (!empty($profesor))
            <flux:modal.trigger wire:click="$set('modalCreacion', true)" name="asignar-asignatura-docente">
                <button class="h-12 px-6 bg-blue-600 text-white rounded-lg text-sm
                hover:bg-blue-700 transition duration-300 cursor-pointer
                dark:bg-blue-700 dark:hover:bg-blue-800">
                Asignar Asignatura al Docente
            </button>
        </flux:modal.trigger>
        @endif
        </div>

    </div>

{{-- Selector de docente como cards --}}
<div class="text-center my-6">
    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Selecciona un docente</h2>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @forelse ($profesores as $profesor)
        <div
            wire:click="$set('profesor_id', {{ $profesor->id }})"
            class="cursor-pointer border rounded-lg p-4 text-center shadow transition
                {{ $profesor_id == $profesor->id
                    ? 'bg-blue-600 text-white border-blue-700'
                    : 'bg-white dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-green-100 dark:hover:bg-blue-700 dark:hover:text-white' }}">
            <div class="font-medium text-md">{{ $profesor->nombre_completo }}</div>
            <div class="text-sm text-gray-700 dark:text-gray-300">Documento: {{ $profesor->documento }}</div>
        </div>
    @empty
        <div class="col-span-full text-center text-gray-500 dark:text-gray-400">No hay docentes disponibles</div>
    @endforelse
</div>


@if (isset($asignaturasProfesor) && count($asignaturasProfesor) > 0)
    <div class="max-w-6xl mx-auto mt-10 p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-lg">
        <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg shadow-sm">
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Información del Docente</h3>
            <p class="text-gray-600 dark:text-gray-300"><strong>Nombre:</strong> {{ $profesor->nombre_completo }}</p>
            <p class="text-gray-600 dark:text-gray-300"><strong>Documento:</strong> {{ $profesor->documento }}</p>
        </div>

        <div class="mt-6">
            <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Asignaturas</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($asignaturasProfesor as $asignaturaProfesor)
                    <div
                        class="relative p-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 flex flex-col gap-2">
                        <p class="text-gray-800 dark:text-gray-200">{{ $asignaturaProfesor->asignatura->nombre }}</p>

                        <!-- Botón Eliminar -->
                        <button wire:click="eliminarAsignaturaProfesor({{ $asignaturaProfesor->id }})"
                            class="mt-auto text-sm bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-700 dark:text-red-100 dark:hover:bg-red-600 px-3 py-1 rounded-md cursor-pointer">
                            Eliminar
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif


    {{-- modal Asignacion de asignaturas al docente --}}
        <flux:modal name="asignar-asignatura-docente" wire:model="modalCreacion" class="md:w-96 lg:w-10/12">
            <div class="space-y-6">
                {{-- Titulo Modal --}}
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Asignacion del Estudiante al Grupo</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">Asigne toda la información requerida.</p>
                </div>

                {{-- Mostrar errores generales --}}
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 dark:bg-red-800/30">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500 dark:text-red-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
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

                {{-- Paso #1: Información Básica --}}
                    <div class="space-y-4">
                        {{-- Docente Actual --}}
                        <div>
                            <label for="grupo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Docente</label>
                            @if (!empty($profesor))
                                <span class="text-red-500">{{ $profesor->nombre_completo }}</span>
                            @endif
                        </div>
                        <!-- Estudiante -->
                        <div>
                            <label for="asignatura_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Asignatura</label>
                            <select id="asignatura_id" wire:model.defer="asignatura_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @if (isset($asignaturas) && count($asignaturas) > 0)
                                <option value="">Selecciona un asignatura</option>
                                @foreach ($asignaturas as $asignatura )
                                <option value="{{ $asignatura->id }}">{{ $asignatura->nombre }}</option>
                                @endforeach
                                @else
                                <option value="">No hay asignaturas disponibles</option>
                                @endif
                            </select>
                            @error('asignatura_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                {{-- Footer modal --}}
                <div class="flex pt-4">
                    <div class="flex-1"></div>
                    <div class="flex space-x-3">
                            <button type="button" wire:click="asignarAsignaturaProfesor()"
                                class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 cursor-pointer">
                                Asignar Asignatura
                            </button>
                    </div>
                </div>
            </div>
        </flux:modal>

{{-- script para escuchar alertas --}}
@push('js')
<script>
    Livewire.on('alerta', (data) => {
        data = data[0];
        Swal.fire({
            title: data['title'],
            text: data['text'],
            icon: data['icon'],
        });
    });
</script>
@endpush
</div>
