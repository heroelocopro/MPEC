<div>
    {{-- parte superior --}}
     <div class="flex items-center justify-between mb-6">
        {{-- Migajas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('colegio-grados') }}">Periodos</flux:breadcrumbs.item>
                @isset($colegio)
                    <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                @endisset
            </flux:breadcrumbs>
        </div>

        {{-- boton de crear --}}

        <div>
            <flux:modal.trigger wire:click="$set('modalCreacion', true)" name="crear-periodo">
                <button class="h-12 px-6 bg-blue-600 text-white rounded-lg text-sm
                    hover:bg-blue-700 transition duration-300 cursor-pointer
                    dark:bg-blue-700 dark:hover:bg-blue-800">
                    Crear Periodo
                </button>
            </flux:modal.trigger>
        </div>

    </div>

    {{-- Filtros --}}

    {{-- Contenido osea mis Periodos Academicos --}}


    <div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-6">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6 text-center">
            Períodos Académicos
        </h2>

        @if ($periodos->isEmpty())
            <p class="text-center text-gray-600 dark:text-gray-300">No hay períodos registrados.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($periodos as $periodo)
                    <div class="border border-gray-300 dark:border-gray-700 rounded-xl p-5 bg-gray-50 dark:bg-gray-800 shadow-sm transition hover:shadow-lg">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-xl font-semibold text-blue-700 dark:text-blue-400">
                                {{ $periodo->nombre }}
                            </h3>
                        <span class="px-3 py-1 text-sm rounded-full
                                    {{ $periodo->es_activo
                                        ? 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200'
                                        : 'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-200' }}">
                            {{ $periodo->es_activo ? 'Activo' : 'Inactivo' }}
                        </span>
                        </div>

                        <div class="text-gray-700 dark:text-gray-300 space-y-1 text-sm">
                            <p><strong>Año:</strong> {{ $periodo->ano }}</p>
                            <p><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y') }}</p>
                            <p><strong>Fin:</strong> {{ \Carbon\Carbon::parse($periodo->fecha_fin)->format('d/m/Y') }}</p>
                        </div>

                        <div class="mt-4 flex justify-end gap-2">
                            <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm">
                                Editar
                            </button>
                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm">
                                Eliminar
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- modal Creacion --}}

    <flux:modal name="crear-periodo" wire:model="modalCreacion" class="md:w-96 lg:w-1/2">
        <div class="space-y-6">
            <!-- Título -->
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Crear Período Académico</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Establece un nuevo período académico para el colegio.</p>
            </div>

            <!-- Mostrar errores -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 dark:bg-red-800/30">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500 dark:text-red-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
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

            <!-- Campos del formulario -->
            <div class="space-y-4">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-900 dark:text-white">Nombre del Período*</label>
                    <input type="text" id="nombre" wire:model.defer="nombre"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Ej: Período 2025 - A">
                    @error('nombre') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Fecha Inicio -->
                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-900 dark:text-white">Fecha de Inicio*</label>
                    <input type="date" id="fecha_inicio" wire:model.defer="fecha_inicio"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('fecha_inicio') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Fecha Fin -->
                <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-900 dark:text-white">Fecha de Finalización*</label>
                    <input type="date" id="fecha_fin" wire:model.defer="fecha_fin"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('fecha_fin') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end pt-4 space-x-3">
                <button type="button" wire:click="crearPeriodoAcademico"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Crear Período
                </button>
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
    Livewire.on('confirmarEliminarAsignatura', (id) => {
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
                    Livewire.dispatch('eliminarAsignatura', id);
                }
            });
        });
</script>
    @endpush

</div>
