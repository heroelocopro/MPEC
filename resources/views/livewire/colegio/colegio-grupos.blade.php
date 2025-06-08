<div>
    {{-- Apartado superior --}}
    <div class="flex items-center justify-between mb-6">
        {{-- Migajas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('colegio-grados') }}">Grupos</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>

        {{-- Botón Crear Grupo --}}
        <div>
            <flux:modal.trigger wire:click="$set('modalCreacion', true)" name="crear-profesor">
                <button class="h-12 px-6 bg-blue-600 text-white rounded-lg text-sm
                    hover:bg-blue-700 transition duration-300 cursor-pointer
                    dark:bg-blue-700 dark:hover:bg-blue-800">
                    Crear Grupo
                </button>
            </flux:modal.trigger>
        </div>
    </div>

    {{-- Mostrar Grupos --}}
    <div class="p-8">
        <div class="max-w-7xl mx-auto">
            @if (!empty($grados))
                <h2 class="text-4xl font-bold text-center text-gray-800 dark:text-gray-100 mb-12">Nuestros Grupos</h2>

                <div class="grid gap-10">
                    @foreach ($grados as $grado)
                        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg dark:shadow-md p-8 transition hover:shadow-2xl dark:hover:shadow-xl">

                            <!-- Título del Grado -->
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-2xl font-bold text-gray-700 dark:text-white">
                                    {{ Str::title($grado->nombre) }}
                                </h3>
                                <span class="text-sm text-gray-400 dark:text-gray-300"> {{ count($grado->grupos) }} grupos</span>
                            </div>

                            <!-- Grupos -->
                            <div class="flex flex-wrap gap-4">
                                @foreach ($grado->grupos as $grupo)
                                    <div class="relative group bg-gradient-to-br from-sky-400 to-indigo-500 text-white font-semibold px-5 py-3 rounded-full shadow-md hover:scale-105 hover:shadow-lg transition">

                                        <div class="flex items-center gap-2">
                                            <!-- Ícono de grupo -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a4 4 0 00-5-4m-6 6h5v-2a4 4 0 00-5-4m-6 6h5v-2a4 4 0 00-5-4m6-6h.01M6 8h.01M6 8a2 2 0 11-.01-4.01A2 2 0 016 8zm6 0a2 2 0 11-.01-4.01A2 2 0 0112 8zm6 0a2 2 0 11-.01-4.01A2 2 0 0118 8z" />
                                            </svg>

                                            <!-- Nombre del grupo -->
                                            <span>{{ $grupo->nombre }}</span>
                                        </div>

                                        <!-- Botones ocultos -->
                                        <div class="absolute -top-2 -right-2 opacity-0 group-hover:opacity-100 transition flex space-x-1">
                                            <!-- Editar -->
                                            <button wire:click="editarGrupo({{ $grupo->id }})"
                                                class="bg-white text-sky-600 p-1 rounded-full hover:bg-sky-100 dark:bg-gray-200 dark:hover:bg-gray-300" title="Editar">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536M16.5 3.5l4 4-11 11H5.5v-4L16.5 3.5z" />
                                                </svg>
                                            </button>


                                            <!-- Eliminar -->
                                            <button wire:click="eliminarGrupo({{ $grupo->id }})" class="bg-white text-rose-500 p-1 rounded-full hover:bg-rose-100 dark:bg-gray-200 dark:hover:bg-rose-200" title="Eliminar">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- modal flux  --}}
    {{-- Modal para creación de grupos --}}
    <flux:modal name="crear-grupo" wire:model="modalCreacion" class="md:w-96 lg:w-6/12">
        <div class="space-y-6">
            {{-- Título --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Crear Nuevo Grupo</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Agrega un nuevo grupo para este colegio.</p>
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
                {{-- Nombre del grupo --}}
                <div>
                    <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del Grupo*</label>
                    <input type="text" id="nombre" wire:model.defer="nombre"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Ej. A, B, C...">
                    @error('nombre') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
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
            </div>

            {{-- Footer modal --}}
            <div class="flex pt-4 justify-end space-x-3">
                <button type="button" wire:click="crearGrupo"
                        class="text-white cursor-pointer bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Crear Grupo
                </button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="editar-grupo" wire:model="modalEdicion" class="md:w-96 lg:w-6/12">
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Editar Grupo</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-300">Modifica los datos del grupo seleccionado.</p>
        </div>

        {{-- Formulario --}}
        <div class="space-y-6">
            <div>
                <label for="nombreEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del Grupo*</label>
                <input type="text" id="nombreEdicion" wire:model.defer="nombreEdicion"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('nombreEdicion') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="gradoIdEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Grado*</label>
                <select id="gradoIdEdicion" wire:model.defer="gradoIdEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Selecciona un grado</option>
                    @foreach ($grados as $grado)
                        <option value="{{ $grado->id }}">{{ $grado->nombre }}</option>
                    @endforeach
                </select>
                @error('gradoIdEdicion') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex pt-4 justify-end space-x-3">
            <button type="button" wire:click="actualizarGrupo"
                    class="text-white cursor-pointer bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                Actualizar Grupo
            </button>
        </div>
    </div>
</flux:modal>



    {{-- JS para alerta con SweetAlert2 --}}
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
