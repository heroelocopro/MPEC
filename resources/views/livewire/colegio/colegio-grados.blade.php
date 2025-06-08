<div>
    {{-- apartado superior --}}
     <div class="flex items-center justify-between mb-6">

         {{-- Migajas de pan --}}
         <div>
             <flux:breadcrumbs>
                 <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                 <flux:breadcrumbs.item href="{{ route('colegio-grados') }}">Grados</flux:breadcrumbs.item>
                 <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
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
     <div class="p-8 ">
         <div class="max-w-7xl mx-auto">

             @if (!empty($grados))

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
                         <h3 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-6 border-b pb-2 border-gray-300 dark:border-gray-700">
                             {{ Str::title($nivel) }}
                         </h3>

                         <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                             @foreach ($gradosPorNivel as $grado)
                                 <div class="relative group bg-white dark:bg-gray-800 border-2
                                     @if($nivel == 'preescolar') border-yellow-400
                                     @elseif($nivel == 'primaria') border-blue-500
                                     @else border-purple-600
                                     @endif
                                     rounded-xl shadow-md hover:shadow-lg transform hover:scale-105 transition duration-300 overflow-hidden">

                                     {{-- Badge superior --}}
                                     <div class="absolute top-3 left-3
                                         text-white px-3 py-1 rounded-full text-xs font-semibold shadow-sm
                                         bg-gradient-to-br
                                         @if ($nivel == 'preescolar')
                                             from-yellow-400 to-yellow-500
                                         @elseif ($nivel == 'primaria')
                                             from-blue-400 to-blue-600
                                         @else
                                             from-purple-500 to-indigo-700
                                         @endif
                                     ">
                                         {{ Str::title($nivel) }}
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

             @endif

         </div>
     </div>

     <flux:modal name="crear-grado" wire:model="modalCreacion" class="md:w-96 lg:w-10/12 ">
    <div class="space-y-6 p-10 bg-white text-gray-900 dark:bg-gray-900 dark:text-white transition-colors duration-300">
        <div>
            <h2 class="text-2xl font-bold">Creación del Estudiante</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-300">Cree toda la información requerida.</p>
        </div>

        {{-- Errores --}}
        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-800/30 border-l-4 border-red-500 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500 dark:text-red-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium">Hay {{ $errors->count() }} error(es) en el formulario</h3>
                    </div>
                </div>
            </div>
        @endif

        {{-- Formulario --}}
        <div class="space-y-4">
            @foreach ([
                ['label' => 'Nombre Completo*', 'model' => 'nombre_completoEdicion', 'type' => 'text', 'placeholder' => 'Nombres y Apellidos'],
                ['label' => 'Número de Documento*', 'model' => 'documentoEdicion', 'type' => 'text', 'placeholder' => 'Ej: 123456789'],
                ['label' => 'Fecha de Nacimiento*', 'model' => 'fecha_nacimientoEdicion', 'type' => 'date']
            ] as $field)
                <div>
                    <label class="block mb-2 text-sm font-medium" for="{{ $field['model'] }}">{{ $field['label'] }}</label>
                    <input type="{{ $field['type'] }}" id="{{ $field['model'] }}" wire:model.defer="{{ $field['model'] }}"
                        placeholder="{{ $field['placeholder'] ?? '' }}"
                        class="bg-gray-50 text-black dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:text-white">
                    @error($field['model'])
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            {{-- Tipo de Documento --}}
            <div>
                <label for="tipo_documentoEdicion" class="block mb-2 text-sm font-medium">Tipo de Documento*</label>
                <select id="tipo_documentoEdicion" wire:model.defer="tipo_documentoEdicion"
                    class="bg-gray-50 text-black dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:text-white">
                    <option value="">Selecciona un tipo</option>
                    @foreach ([
                        'RC' => 'Registro Civil',
                        'TI' => 'Tarjeta de Identidad',
                        'CC' => 'Cédula de Ciudadanía',
                        'TE' => 'Tarjeta de Extranjería',
                        'CE' => 'Cédula de Extranjería',
                        'NIT' => 'NIT',
                        'PP' => 'Pasaporte',
                        'PEP' => 'Permiso Especial',
                        'DIE' => 'Documento Extranjero'
                    ] as $key => $label)
                        <option value="{{ $key }}">{{ $label }} ({{ $key }})</option>
                    @endforeach
                </select>
                @error('tipo_documentoEdicion')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Género --}}
            <div>
                <label for="generoEdicion" class="block mb-2 text-sm font-medium">Género</label>
                <select id="generoEdicion" wire:model.defer="generoEdicion"
                    class="bg-gray-50 text-black dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:text-white">
                    <option value="">Seleccione</option>
                    <option value="masculino">Masculino</option>
                    <option value="femenino">Femenino</option>
                    <option value="otro">Otro</option>
                    <option value="prefiero_no_decir">Prefiero no decir</option>
                </select>
                @error('generoEdicion')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Footer modal --}}
        <div class="flex pt-4">
            <div class="flex-1"></div>
            <div class="flex space-x-3">
                <button type="button" wire:click="editarEstudiante()"
                    class="text-white cursor-pointer bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                    Crear Estudiante
                </button>
            </div>
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
     </script>
     @endpush
 </div>
