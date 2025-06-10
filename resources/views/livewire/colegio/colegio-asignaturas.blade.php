<div>
    {{-- Encabezado superior --}}
    <div class="flex items-center justify-between mb-6">
        {{-- Migas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('colegio-grados') }}">Asignaturas</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>

        {{-- Botón Crear Asignatura --}}
        <div>
            <flux:modal.trigger wire:click="abrirModalCreacion" name="crear-asignatura">
                <button
                    class="h-12 px-6 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition duration-300 cursor-pointer dark:bg-blue-700 dark:hover:bg-blue-800">
                    Crear Asignatura
                </button>
            </flux:modal.trigger>
        </div>
    </div>

    {{-- Presentación de asignaturas --}}
    <div class="p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center dark:text-white">Asignaturas</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @if (isset($asignaturas) && count($asignaturas) > 0)
                @foreach ($asignaturas as $asignatura)
                    <div style="background-color: {{ $asignatura->color }}"
                        class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow group dark:bg-gray-800">
                        <h3 class="text-xl font-semibold text-gray-700 text-center dark:text-gray-200">
                            {{ $asignatura->nombre }}</h3>
                        <p class="text-center my-5 text-gray-600 dark:text-gray-300">{{ $asignatura->descripcion }}</p>
                        <div class="flex justify-center gap-4 mt-4">
                            <button wire:click="cargarEditar({{ $asignatura->id }})"
                                class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition cursor-pointer dark:bg-blue-700 dark:hover:bg-blue-800">
                                Editar
                            </button>
                            <button wire:click="$dispatch('confirmarEliminarAsignatura',{id: {{ $asignatura->id }}})"
                                class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 transition cursor-pointer dark:bg-red-700 dark:hover:bg-red-800">
                                Eliminar
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center col-span-full text-gray-600 dark:text-gray-400">No hay asignaturas registradas.</p>
            @endif
        </div>
    </div>

    <flux:modal name="crear-asignatura" wire:model="modalCreacion" class="md:w-96 lg:w-10/12" :dismissible="false">
        <div class="space-y-6">
            {{-- Título --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Creación de Asignatura</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Complete todos los campos requeridos para registrar una asignatura.</p>
            </div>

            {{-- Errores --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 dark:bg-red-800/30">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-500 dark:text-red-300" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor">
                            <path fill-rule="evenodd" d="..." clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Hay {{ $errors->count() }} error(es) en el formulario
                            </h3>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Campos del formulario --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nombre --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
                    <input type="text" wire:model.defer="nombre" class="input-form dark:bg-gray-700 dark:text-white"
                        placeholder="Ej: Matemáticas">
                    @error('nombre') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Área --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Área</label>
                    <input type="text" wire:model.defer="area" class="input-form dark:bg-gray-700 dark:text-white"
                        placeholder="Ej: Ciencias Exactas">
                    @error('area') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Descripción --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Descripción</label>
                    <textarea wire:model.defer="descripcion" class="input-form dark:bg-gray-700 dark:text-white" rows="3"
                        placeholder="Opcional..."></textarea>
                    @error('descripcion') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Grado mínimo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Grado mínimo</label>
                    <input type="number" wire:model="grado_minimo" class="input-form dark:bg-gray-700 dark:text-white"
                        placeholder="Ej: 1">
                    @error('grado_minimo') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Grado máximo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Grado máximo</label>
                    <input type="number" wire:model="grado_maximo" class="input-form dark:bg-gray-700 dark:text-white"
                        placeholder="Ej: 11">
                    @error('grado_maxino') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Carga horaria --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Carga horaria (semanal)</label>
                    <input type="number" wire:model.defer="carga_horaria" class="input-form dark:bg-gray-700 dark:text-white"
                        placeholder="Ej: 4">
                    @error('carga_horaria') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Tipo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Tipo</label>
                    <select wire:model.defer="tipo" class="input-form dark:bg-gray-700 dark:text-white">
                        <option value="">Selecciona tipo</option>
                        <option value="obligatoria">Obligatoria</option>
                        <option value="optativa">Optativa</option>
                    </select>
                    @error('tipo') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Estado</label>
                    <select wire:model.defer="estado" class="input-form dark:bg-gray-700 dark:text-white">
                        <option value="">Selecciona estado</option>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                    @error('estado') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Color --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Color</label>
                    <input type="color" wire:model.defer="color" class="w-12 h-10 p-0 border-none">
                    @error('color') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex justify-end pt-4 space-x-3">
                <button type="button" wire:click="crearAsignatura"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                    Guardar Asignatura
                </button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="editar-asignatura" wire:model="modalEdicion" class="md:w-96 lg:w-10/12" :dismissible="false">
        <div class="space-y-6">
            {{-- Título --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Edición de Asignatura</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Complete todos los campos requeridos para registrar una asignatura.</p>
            </div>

            {{-- Errores --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 dark:bg-red-800/30">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-500 dark:text-red-300" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor">
                            <path fill-rule="evenodd" d="..." clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Hay {{ $errors->count() }} error(es) en el formulario
                            </h3>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Campos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nombre --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
                    <input type="text" wire:model.defer="nombre" class="input-form dark:bg-gray-700 dark:text-white"
                        placeholder="Ej: Matemáticas">
                    @error('nombre') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Área --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Área</label>
                    <input type="text" wire:model.defer="area" class="input-form dark:bg-gray-700 dark:text-white"
                        placeholder="Ej: Ciencias Exactas">
                    @error('area') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Descripción --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Descripción</label>
                    <textarea wire:model.defer="descripcion" class="input-form dark:bg-gray-700 dark:text-white" rows="3"
                        placeholder="Opcional..."></textarea>
                    @error('descripcion') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Grado mínimo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Grado mínimo</label>
                    <input type="number" wire:model="grado_minimo" class="input-form dark:bg-gray-700 dark:text-white"
                        placeholder="Ej: 1">
                    @error('grado_minimo') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Grado máximo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Grado máximo</label>
                    <input type="number" wire:model="grado_maximo" class="input-form dark:bg-gray-700 dark:text-white"
                        placeholder="Ej: 11">
                    @error('grado_maximo') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Carga horaria --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Carga horaria (semanal)</label>
                    <input type="number" wire:model.defer="carga_horaria" class="input-form dark:bg-gray-700 dark:text-white"
                        placeholder="Ej: 4">
                    @error('carga_horaria') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Tipo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Tipo</label>
                    <select wire:model.defer="tipo" class="input-form dark:bg-gray-700 dark:text-white">
                        <option value="">Selecciona tipo</option>
                        <option value="obligatoria">Obligatoria</option>
                        <option value="optativa">Optativa</option>
                    </select>
                    @error('tipo') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Estado</label>
                    <select wire:model.defer="estado" class="input-form dark:bg-gray-700 dark:text-white">
                        <option value="">Selecciona estado</option>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                    @error('estado') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Color --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Color</label>
                    <input type="color" wire:model.defer="color" class="w-12 h-10 p-0 border-none">
                    @error('color') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex justify-end pt-4 space-x-3">
                <button type="button" wire:click="guardarAsignatura"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                    Guardar Cambios
                </button>
            </div>
        </div>
    </flux:modal>
</div>
