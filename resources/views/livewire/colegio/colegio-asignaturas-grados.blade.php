<div>
    {{-- Migas de pan y acciones --}}
    <div class="flex items-center justify-between mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('colegio-grados') }}">Docente - Asignaturas</flux:breadcrumbs.item>
            @isset($colegio)
                <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
            @endisset
        </flux:breadcrumbs>

        @if (!empty($grado_id))
            <flux:modal.trigger wire:click="$set('modalCreacion', true)" name="asignacion-asignatura-grado">
                <button class="h-12 px-6 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                    Asignar Asignatura al Grado
                </button>
            </flux:modal.trigger>
        @endif
    </div>

    {{-- Selector de grado --}}
    <h2 class="text-lg font-semibold text-gray-800 dark:text-white text-center mb-4">Selecciona un grado</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        @forelse ($grados as $g)
            <div
                wire:click="$set('grado_id', {{ $g->id }})"
                class="cursor-pointer border rounded-lg p-4 text-center shadow transition
                    {{ $grado_id == $g->id
                        ? 'bg-blue-600 text-white border-blue-700 dark:bg-white dark:text-black dark:border-blue-700'
                        : 'bg-white text-gray-900 border-gray-300 hover:bg-blue-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700' }} ">
                <span class="font-medium text-md">{{ $g->nombre }}</span>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500">No hay grados disponibles</div>
        @endforelse
    </div>

    {{-- Información del grado y asignaturas --}}
    @if (!empty($asignaturasGrados))
        <div class="max-w-6xl mx-auto mt-10 p-6 rounded-2xl shadow-lg bg-white dark:bg-gray-800">
            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Información del Grado</h3>
                <p><strong>Nombre:</strong> {{ $grado->nombre }}</p>
                <p><strong>Descripción:</strong> {{ $grado->descripcion }}</p>
            </div>

            <div class="mt-6">
                <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Asignaturas</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($asignaturasGrados as $item)
                        <div class="relative p-3 bg-white dark:bg-gray-700 border rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                            <p>{{ $item->asignatura->nombre }}</p>
                            <button
                                wire:click="$dispatch('eliminar', { id: {{ $item->id }} })"
                                class="mt-2 text-sm bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-700 dark:text-red-100 dark:hover:bg-red-600 px-3 py-1 rounded-md">
                                Eliminar
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @elseif (!empty($grado_id))
        <h3 class="text-gray-700 dark:text-gray-300 mt-6 text-center">No se encontraron asignaturas en ese grado</h3>
    @endif

    {{-- Modal para asignación --}}
    <flux:modal name="asignacion-asignatura-grado" wire:model="modalCreacion" class="md:w-96 lg:w-10/12">
        <div class="space-y-6">
            <h2 class="text-2xl font-bold">Asignación de la Asignatura</h2>
            <p class="text-gray-600 dark:text-gray-300">Seleccione asignaturas para el grado actual.</p>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 dark:bg-red-800/30">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                        Hay {{ $errors->count() }} error(es) en el formulario
                    </h3>
                </div>
            @endif

            <div class="space-y-4">
                {{-- Grado actual --}}
                <div>
                    <label class="block text-sm font-medium">Grado</label>
                    <span class="text-red-500 font-bold">{{ $grado->nombre ?? 'N/A' }}</span>
                </div>

                {{-- Selector de asignaturas --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Asignaturas</label>
                    <select multiple wire:model.defer="asignaturasSeleccionadas"
                        class="w-full p-2 border rounded-lg bg-white dark:bg-gray-700">
                        @forelse ($asignaturas as $asignatura)
                            <option value="{{ $asignatura->id }}">{{ $asignatura->nombre }}</option>
                        @empty
                            <option value="">No hay asignaturas disponibles</option>
                        @endforelse
                    </select>
                    @error('asignaturasSeleccionadas')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Footer modal --}}
            <div class="flex justify-end pt-4">
                <button wire:click="asignarAsignaturaGrado" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Asignar Asignaturas
                </button>
            </div>
        </div>
    </flux:modal>

    {{-- JS para confirmación y alertas --}}
    @push('js')
        <script>
            Livewire.on('eliminar', (id) => {
                Swal.fire({
                    title: "¿Estas seguro?",
                    text: "No puedes deshacer esta acción",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, eliminar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('eliminarAsignacionAsignaturaGrado', id);
                    }
                });
            });

            Livewire.on('alerta', (data) => {
                const d = data[0][0];
                console.log(d);
                Swal.fire({ title: d.title, text: d.text, icon: d.icon });
            });
        </script>
    @endpush
</div>
