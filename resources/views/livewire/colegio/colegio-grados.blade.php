<div>
    {{-- apartado superior --}}
     <div class="flex items-center justify-between mb-6">

         {{-- Migajas de pan --}}
         <div>
             <flux:breadcrumbs>
                 <flux:breadcrumbs.item href="{{ route('colegio-inicio') }}">Panel Principal</flux:breadcrumbs.item>
                 <flux:breadcrumbs.item href="{{ route('colegio-grados') }}">Grados</flux:breadcrumbs.item>
                 @isset($colegio)
                    <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                @endisset
             </flux:breadcrumbs>
         </div>

         {{-- Botón Crear --}}
         <div>
             <flux:modal.trigger wire:click="$set('modalCreacion', true)" name="crear-grado">
                 <button class="h-12 px-6 bg-blue-600 text-white rounded-lg text-sm
                     hover:bg-blue-700 transition duration-300 cursor-pointer
                     dark:bg-blue-700 dark:hover:bg-blue-800">
                     Crear Grado
                 </button>
             </flux:modal.trigger>
         </div>

     </div>

{{-- datos --}}
<div class="p-8">
    <div class="max-w-7xl mx-auto">

        @if ($grados->isNotEmpty())

            @php
                $gradosAgrupados = [];
                foreach ($grados as $grado) {
                    $gradosAgrupados[$grado->nivel][] = $grado;
                }

                $iconos = [
                    'preescolar' => '🎨',
                    'primaria' => '📚',
                    'bachillerato' => '🎓',
                ];
            @endphp

            @foreach ($gradosAgrupados as $nivel => $gradosPorNivel)
                <div class="mb-12">
                    <h3
                        class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-6 border-b pb-2 border-gray-300 dark:border-gray-700">
                        {{ Str::title($nivel) }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach ($gradosPorNivel as $grado)
                            <div
                                class="relative group bg-white dark:bg-gray-800 border-2
                                    @if($nivel == 'preescolar') border-yellow-400
                                    @elseif($nivel == 'primaria') border-blue-500
                                    @else border-purple-600
                                    @endif
                                    rounded-xl shadow-md hover:shadow-lg transform hover:scale-105 transition duration-300 overflow-hidden">

                                {{-- Badge superior --}}
                                <div
                                    class="absolute top-3 left-3 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-sm
                                        bg-gradient-to-br
                                        @if ($nivel == 'preescolar')
                                            from-yellow-400 to-yellow-500
                                        @elseif ($nivel == 'primaria')
                                            from-blue-400 to-blue-600
                                        @else
                                            from-purple-500 to-indigo-700
                                        @endif">
                                    {{ Str::title($nivel) }}
                                </div>

                                {{-- Botones de acción (editar / eliminar) --}}
                                <div class="absolute top-3 right-3 flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    {{-- Editar --}}
                                    <button wire:click="editarGrado({{ $grado->id }})"
                                        class="p-2 cursor-pointer bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-md"
                                        title="Editar grado">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L12 20l-4 1 1-4 9.586-9.586z" />
                                        </svg>
                                    </button>

                                    {{-- Eliminar --}}
                                    <button wire:click="$dispatch('confirmarEliminarGrado', {id:{{ $grado->id }} })"

                                        class="p-2 cursor-pointer bg-red-600 hover:bg-red-700 text-white rounded-full shadow-md"
                                        title="Eliminar grado">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Ícono y datos --}}
                                <div class="p-6 flex flex-col items-center text-center">
                                    <div class="text-4xl mb-3">
                                        {{ $iconos[$nivel] ?? '🏫' }}
                                    </div>
                                    <h4 class="text-xl font-bold mb-1 text-gray-800 dark:text-gray-100">
                                        {{ $grado->nombre }}
                                    </h4>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm">
                                        {{ $grado->descripcion }}
                                    </p>
                                </div>

                                {{-- Edad --}}
                                <div class="absolute bottom-0 right-0 p-2 text-gray-400 text-xs">
                                    {{ $grado->edad_referencia }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach
        @else
        <p class="text-center">No hay Grados</p>
        @endif

    </div>
</div>


    {{-- MODAL CREAR --}}
    <flux:modal name="crear-grado" wire:model="modalCreacion" class="md:w-96 lg:w-10/12">
        <div class="space-y-6 p-10 bg-white text-gray-900 dark:bg-gray-900 dark:text-white">
            <h2 class="text-2xl font-bold mb-2">Crear Nuevo Grado</h2>
            <p class="text-gray-500 dark:text-gray-300 mb-4">Complete los datos del grado a registrar.</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Nombre*</label>
                    <input type="text" wire:model.defer="nombre" placeholder="Ej: Primero, Sexto..."
                        class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                    @error('nombre') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Nivel*</label>
                    <select wire:model.defer="nivel"
                        class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                        <option value="">Seleccione un nivel</option>
                        <option value="preescolar">Preescolar</option>
                        <option value="primaria">Primaria</option>
                        <option value="secundaria">Secundaria</option>
                        <option value="media">Media</option>
                    </select>
                    @error('nivel') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Edad de Referencia</label>
                    <input type="text" wire:model.defer="edad_referencia" placeholder="Ej: 10-11 años"
                        class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Descripción</label>
                    <textarea wire:model.defer="descripcion" rows="3"
                        placeholder="Ej: Preparación para secundaria..."
                        class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700"></textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" wire:click="crearGrado"
                    class="bg-green-600 cursor-pointer hover:bg-green-700 text-white px-4 py-2 rounded">Guardar</button>
                <button type="button" wire:click="$set('modalCreacion', false)"
                    class="bg-gray-400 cursor-pointer hover:bg-gray-500 text-white px-4 py-2 rounded">Cancelar</button>
            </div>
        </div>
    </flux:modal>

    {{-- MODAL EDITAR --}}
    <flux:modal name="editar-grado" wire:model="modalEdicion" class="md:w-96 lg:w-10/12">
        <div class="space-y-6 p-10 bg-white text-gray-900 dark:bg-gray-900 dark:text-white">
            <h2 class="text-2xl font-bold mb-2">Editar Grado</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Nombre*</label>
                    <input type="text" wire:model.defer="nombreEdicion"
                        class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                    @error('nombreEdicion') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Nivel*</label>
                    <select wire:model.defer="nivelEdicion"
                        class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                        <option value="">Seleccione un nivel</option>
                        <option value="preescolar">Preescolar</option>
                        <option value="primaria">Primaria</option>
                        <option value="secundaria">Secundaria</option>
                        <option value="media">Media</option>
                    </select>
                    @error('nivelEdicion') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Edad de Referencia</label>
                    <input type="text" wire:model.defer="edad_referenciaEdicion"
                        class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Descripción</label>
                    <textarea wire:model.defer="descripcionEdicion" rows="3"
                        class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Estado</label>
                    <select wire:model.defer="estadoEdicion"
                        class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" wire:click="actualizarGrado"
                    class="bg-blue-600 cursor-pointer hover:bg-blue-700 text-white px-4 py-2 rounded">Actualizar</button>
                <button type="button" wire:click="$set('modalEdicion', false)"
                    class="bg-gray-400 cursor-pointer hover:bg-gray-500 text-white px-4 py-2 rounded">Cancelar</button>
            </div>
        </div>
    </flux:modal>


     {{-- JS --}}
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
         Livewire.on('confirmarEliminarGrado', (id) => {
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
                    Livewire.dispatch('eliminarGrado', id);
                }
            });
            });
     </script>
     @endpush
 </div>
