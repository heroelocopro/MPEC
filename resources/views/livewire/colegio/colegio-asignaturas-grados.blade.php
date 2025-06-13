<div>
     {{-- breadcrumb --}}
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
            @if (!empty($grado_id))
            <flux:modal.trigger wire:click="$set('modalCreacion', true)" name="asignacion-asignatura-grado">
                <button class="h-12 px-6 bg-blue-600 text-white rounded-lg text-sm
                hover:bg-blue-700 transition duration-300 cursor-pointer
                dark:bg-blue-700 dark:hover:bg-blue-800">
                Asignar Asignatura al Grado
            </button>
        </flux:modal.trigger>
        @endif
        </div>

    </div>

{{-- Selector de grado como cards --}}
<div class="text-center mb-4">
    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Selecciona un grado</h2>
</div>

<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
    @forelse ($grados as $grado)
        <div
            wire:click="$set('grado_id', {{ $grado->id }})"
            class="cursor-pointer border rounded-lg p-4 text-center shadow transition
                {{ $grado_id == $grado->id
                    ? 'bg-blue-600 text-white border-blue-700 dark:bg-blue-500 dark:border-blue-400'
                    : 'bg-white text-gray-900 border-gray-300 hover:bg-blue-100 hover:text-gray-900 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-blue-700 dark:hover:text-white' }}">
            <span class="font-medium text-md">{{ $grado->nombre }}</span>
        </div>
    @empty
        <div class="col-span-full text-center text-gray-500 dark:text-gray-400">No hay grados disponibles</div>
    @endforelse
</div>



    {{-- mostrando --}}
    @if (isset($asignaturasGrados) && count($asignaturasGrados) > 0)
    <div class="max-w-6xl mx-auto mt-10 p-6 rounded-2xl shadow-lg bg-white dark:bg-gray-800">
        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Información del Grado</h3>
            <p class="text-gray-600 dark:text-gray-300"><strong>Nombre:</strong> {{ $asignaturasGrados[0]->grado->nombre }}</p>
            <p class="text-gray-600 dark:text-gray-300"><strong>Descripcion:</strong> {{ $asignaturasGrados[0]->grado->descripcion }}</p>
        </div>

        <div class="mt-6">
            <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Asignaturas</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($asignaturasGrados as $item)
                    <div class="relative p-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 flex flex-col gap-2 text-gray-900 dark:text-gray-100">
                        <p>{{ $item->asignatura->nombre }}</p>

                        <!-- Botón Eliminar -->
                        <button
                            wire:click="$dispatch('eliminar', { id: {{ $item->id }} })"
                            class="mt-auto text-sm bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-700 dark:text-red-100 dark:hover:bg-red-600 px-3 py-1 rounded-md cursor-pointer transition">
                            Eliminar
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@else
    @if ($grado_id != null && $grado_id != '')
        <h3 class="text-gray-700 dark:text-gray-300 mt-6 text-center">No se encontraron asignaturas en ese grado</h3>
    @endif
@endif


    {{-- modal de asignacion --}}
    <flux:modal name="asignacion-asignatura-grado" wire:model="modalCreacion" class="md:w-96 lg:w-10/12">
        <div class="space-y-6">
            {{-- Titulo Modal --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Asignacion de la asignatura al Grupo</h2>
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
                    {{-- Grupo Actual --}}
                    <div>
                        <label for="grupo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Grado</label>
                        @if (!empty($grado))
                            <span class="text-red-500">{{ $grado->nombre }}</span>
                        @endif
                    </div>
                    <!-- Asignaturas -->
                    <div>
                        <label for="asignatura_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Asignatura</label>
                        <select id="asignatura_id" wire:model.defer="asignatura_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @if (isset($asignaturas) && count($asignaturas) > 0)
                            <option value="">Selecciona una asignatura</option>
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
                        <button type="button" wire:click="asignarAsignaturaGrado()"
                            class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 cursor-pointer">
                            Asignar Asignatura Grado
                        </button>
                </div>
            </div>
        </div>
    </flux:modal>
    {{-- script js --}}
@push('js')
<script>
    Livewire.on('eliminar', (id) => {
        Swal.fire({
        title: "Estas Seguro?",
        text: "No se puedes deshacer esto",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminalo!"
        }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch('eliminarAsignacionAsignaturaGrado', id);
        }
        });
    });
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
