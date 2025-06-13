<div>
    {{-- apartado superior --}}
    <div class="flex items-center justify-between mb-6">
        {{-- Migajas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('colegio-grados') }}">Matrículas</flux:breadcrumbs.item>
                @isset($colegio)
                    <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                @endisset
            </flux:breadcrumbs>
        </div>
    </div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl bg-white dark:bg-gray-800">
        {{-- filtros --}}
            <div class="flex items-center gap-2 w-full">
                <!-- Select (10%) - Versión corregida -->
                <select wire:model.live="paginacion"
                        class="w-[10%] h-12 px-3 rounded-lg border border-gray-300 bg-white text-gray-700
                            dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300
                            focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <select wire:model.live="gradoFilter"
                        class="w-[10%] h-12 px-3 rounded-lg border border-gray-300 bg-white text-gray-700
                            dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300
                            focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Grados</option>
                    @foreach ($grados as $grado )
                        <option value="{{ $grado->id }}">{{ $grado->nombre }}</option>
                    @endforeach
                </select>

                <!-- Input (80%) - Versión corregida -->
                <input wire:model.live.debounce.250ms="buscador"
                    type="text"
                    placeholder="Buscar Estudiante"
                    class="w-[80%] h-12 px-4 rounded-lg border border-gray-300 bg-white text-gray-700
                            dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:placeholder-gray-400
                            focus:outline-none focus:ring-2 focus:ring-blue-500">

                 {{-- Botón Crear Matrícula --}}
                <div>
                    <flux:modal.trigger wire:click="$set('modalCreacion', true)" name="crear-matricula">
                        <button class="h-12 px-6 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition duration-300 cursor-pointer dark:bg-blue-700 dark:hover:bg-blue-800">
                            Crear Matrícula
                        </button>
                    </flux:modal.trigger>
                </div>
            </div>
        </div>

{{-- Tabla Matrículas --}}
<div class="mt-2">
    <div class="overflow-x-auto shadow-md sm:rounded-lg">
        <table class="min-w-full bg-white dark:bg-gray-900 border rounded-lg">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-sm uppercase font-medium tracking-wider">
                <tr>
                    <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('id')">
                        ID
                        @if($sortField === 'id')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('estudiante_id')">
                        Estudiante
                        @if($sortField === 'estudiante_id')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('sede_id')">
                        Sede
                        @if($sortField === 'sede_id')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('grado_id')">
                        Grado
                        @if($sortField === 'grado_id')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('tipo_matricula')">
                        Tipo
                        @if($sortField === 'tipo_matricula')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('estado')">
                        Estado
                        @if($sortField === 'estado')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('fecha_matricula')">
                        Fecha Matrícula
                        @if($sortField === 'fecha_matricula')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 dark:text-gray-300 text-sm">
                @forelse($matriculas as $matricula)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="py-3 px-6">{{ $matricula->id }}</td>
                        <td class="py-3 px-6">{{ $matricula->estudiante->nombre_completo }}</td>
                        <td class="py-3 px-6">{{ $matricula->estudiante->sede?->nombre ?? 'Sede Principal' }}</td>
                        <td class="py-3 px-6">{{ $matricula->grado->nombre }}</td>
                        <td class="py-3 px-6 capitalize">{{ $matricula->tipo_matricula }}</td>
                        <td class="py-3 px-6">
                            @if($matricula->estado)
                                <span class="bg-green-100 text-green-700 dark:bg-green-700 dark:text-green-100 px-2 py-1 rounded-full text-xs">Activo</span>
                            @else
                                <span class="bg-red-100 text-red-700 dark:bg-red-700 dark:text-red-100 px-2 py-1 rounded-full text-xs">Inactivo</span>
                            @endif
                        </td>
                        <td class="py-3 px-6">{{ \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button wire:click="cargarMatriculaEdicion({{ $matricula->id }})" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-xs cursor-pointer">
                                Editar
                            </button>
                            <button wire:click="$dispatch('confirmarEliminarMatricula', { id: {{ $matricula->id }} })" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-xs cursor-pointer">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">No hay matrículas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if ($matriculas->hasPages())
            <div class="mt-4">
                {{ $matriculas->links() }}
            </div>
        @endif
    </div>
</div>


    {{-- Modal de asignación o creación de matrículas --}}
    <flux:modal name="crear-matricula" wire:model="modalCreacion" class="md:w-96 lg:w-10/12 lg:h-7/12">
        <div class="space-y-6">
            {{-- Título --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Asignación de Matrícula</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-300">No olvides los detalles importantes.</p>
            </div>

            {{-- Errores --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 dark:bg-red-800/30">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500 dark:text-red-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
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

            {{-- Formulario --}}
            <div class="space-y-6">
                {{-- Estudiante --}}
                <div>
                    <label for="estudiante_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Estudiante*</label>
                    <select id="estudiante_id" wire:model.defer="estudiante_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @if (isset($estudiantesSinMatricula) && count($estudiantesSinMatricula))
                            <option value="">Selecciona un estudiante</option>
                            @foreach ($estudiantesSinMatricula as $estudiante)
                                <option value="{{ $estudiante->id }}">{{ $estudiante->nombre_completo }} - {{ $estudiante->documento }}</option>
                            @endforeach
                        @else
                            <option value="">No hay estudiantes sin matricular</option>
                        @endif
                    </select>
                    @error('estudiante_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Grado --}}
                <div>
                    <label for="grado_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Grado*</label>
                    <select id="grado_id" wire:model.defer="grado_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Selecciona un grado</option>
                        @foreach ($grados as $grado)
                            <option value="{{ $grado->id }}">{{ $grado->nombre }}</option>
                        @endforeach
                    </select>
                    @error('grado_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Tipo --}}
                <div>
                    <label for="tipo_matricula" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de Matrícula*</label>
                    <select id="tipo_matricula" wire:model.defer="tipo_matricula"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Selecciona un tipo</option>
                        <option value="nueva">Nueva</option>
                        <option value="renovacion">Renovación</option>
                        <option value="traslado">Traslado</option>
                    </select>
                    @error('tipo_matricula') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Footer modal --}}
            <div class="flex pt-4 justify-end space-x-3">
                <button type="button" wire:click="guardarMatricula"
                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                    Guardar Matrícula
                </button>
            </div>
        </div>
    </flux:modal>

{{-- Modal de edición de matrículas --}}
<flux:modal name="editar-matricula" wire:model="modalEdicion" class="md:w-96 lg:w-10/12 lg:h-8/12">
    <div class="space-y-6">
        {{-- Título --}}
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Edición de Matrícula</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-300">Actualiza los datos necesarios.</p>
        </div>

        {{-- Errores --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 dark:bg-red-800/30">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500 dark:text-red-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
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

        {{-- Formulario --}}
        <div class="space-y-6">
            {{-- estudiante actual --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Estudiante</label>
                <input type="text" readonly
                       value="{{ $estudianteEdicion }}"
                       class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
            </div>
            {{-- Grado --}}
            <div>
                <label for="grado_idEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Grado*</label>
                <select id="grado_idEdicion" wire:model.defer="grado_idEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Selecciona un grado</option>
                    @foreach ($grados as $grado)
                        <option value="{{ $grado->id }}">{{ $grado->nombre }}</option>
                    @endforeach
                </select>
                @error('grado_idEdicion') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Tipo --}}
            <div>
                <label for="tipo_matriculaEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de Matrícula*</label>
                <select id="tipo_matriculaEdicion" wire:model.defer="tipo_matriculaEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Selecciona un tipo</option>
                    <option value="nueva">Nueva</option>
                    <option value="renovacion">Renovación</option>
                    <option value="traslado">Traslado</option>
                </select>
                @error('tipo_matriculaEdicion') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Fecha --}}
            <div>
                <label for="fecha_matriculaEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha de Matrícula*</label>
                <input type="date" id="fecha_matriculaEdicion" wire:model.defer="fecha_matriculaEdicion"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('fecha_matriculaEdicion') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Footer modal --}}
        <div class="flex pt-4 justify-end space-x-3">
            <button type="button" wire:click="editarMatricula"
                    class="text-white cursor-pointer bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                Guardar Cambios
            </button>
        </div>
    </div>
</flux:modal>


    {{-- Script de alerta --}}
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

            Livewire.on('confirmarEliminarMatricula', (id) => {
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
                    Livewire.dispatch('eliminarMatricula', id);
                }
            });
        });
        </script>
    @endpush
</div>
