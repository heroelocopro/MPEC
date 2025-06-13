<div>
    {{-- Migajas de pan --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Docentes</flux:breadcrumbs.item>
        @isset($colegio)
                    <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                @endisset
    </flux:breadcrumbs>
    {{-- fin migajas de pan --}}

    {{-- espaciado --}}
    <br>
    {{-- espaciado --}}
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl bg-white dark:bg-gray-800">
    {{-- filtros --}}
        <div class="flex items-center gap-2 w-full">
            <!-- Select (10%) - Versión corregida -->
            <select wire:model="paginacion"
                    class="w-[10%] h-12 px-3 rounded-lg border border-gray-300 bg-white text-gray-700
                        dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300
                        focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>

            <!-- Input (80%) - Versión corregida -->
            <input wire:model.live.debounce.250ms="buscador"
                type="text"
                placeholder="Buscar Profesor"
                class="w-[80%] h-12 px-4 rounded-lg border border-gray-300 bg-white text-gray-700
                        dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:placeholder-gray-400
                        focus:outline-none focus:ring-2 focus:ring-blue-500">

            <!-- Botón (10%) -->
            <flux:modal.trigger wire:click="$set('modalCreacion',true)" name="crear-profesor">
                <button class="w-[10%] h-12 bg-blue-600 text-white rounded-lg text-sm
                            hover:bg-blue-700 transition cursor-pointer
                            dark:bg-blue-700 dark:hover:bg-blue-800">
                    Crear Profesor
                </button>
            </flux:modal.trigger>
        </div>
    </div>

    {{-- espaciado --}}
    <br>
    {{-- espaciado --}}

    {{-- tabla --}}
    <div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('id')">
                            id
                            @if($sortField === 'id')
                                @if($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        {{-- <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('colegio_id')">
                            colegio
                            @if($sortField === 'colegio_id')
                                @if($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th> --}}
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('sede_id')">
                            sede
                            @if($sortField === 'sede_id')
                                @if($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('nombre_completo')">
                            nombre completo
                            @if($sortField === 'nombre_completo')
                                @if($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('tipo_documento')">
                            tipo documento
                            @if($sortField === 'tipo_documento')
                                @if($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('documento')">
                            documento
                            @if($sortField === 'documento')
                                @if($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('correo')">
                            correo
                            @if($sortField === 'correo')
                                @if($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('telefono')">
                            telefono
                            @if($sortField === 'telefono')
                                @if($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('titulo_academico')">
                            título académico
                            @if($sortField === 'titulo_academico')
                                @if($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('user_id')">
                            usuario
                            @if($sortField === 'user_id')
                                @if($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('created_at')">
                            fecha creación
                            @if($sortField === 'created_at')
                                @if($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            <span class="sr-only">Edit</span>
                        </th>
                    </tr>

                </thead>
                <tbody>
                    @if (!empty($profesores))
                    @foreach ($profesores as $profesor )
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                           {{ $profesor->id}}
                        </th>
                        {{-- <td class="px-6 py-4">
                            {{ $profesor->colegio->nombre }}
                        </td> --}}
                        <td class="px-6 py-4">
                          {{ $profesor->sede ? $profesor->sede->nombre : 'Sede Principal' }}

                        </td>
                        <td class="px-6 py-4">
                            {{ $profesor->nombre_completo }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $profesor->tipo_documento }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $profesor->documento }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $profesor->correo }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $profesor->telefono }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $profesor->titulo_academico }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $profesor->usuario->email }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $profesor->created_at }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="cargarProfesor({{ $profesor->id }})" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs
                                         dark:bg-blue-600 dark:hover:bg-blue-700 cursor-pointer w-full m-1">
                                Editar
                            </button>
                            <button wire:click="$dispatch('confirmarEliminarProfesor', { id: {{ $profesor->id }} })"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-xs
                                           dark:bg-red-600 dark:hover:bg-red-700 cursor-pointer w-full m-1">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>

            {{-- paginacion --}}
        @if ($profesores->hasPages())
            {{ $profesores->links() }}
        @endif

    </div>

    {{-- Modal de Creacion --}}
        <flux:modal name="crear-profesor" wire:model="modalCreacion" class="md:w-96 lg:w-10/12">
            <div class="space-y-6">
                <!-- Progress indicator -->
                <div class="flex justify-between items-center mb-6">
                    @for($i = 1; $i <= $totalSteps; $i++)
                        <div class="flex-1 flex items-center">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center
                                    {{ $i < $currentStep ? 'bg-green-500 text-white' :
                                    ($i == $currentStep ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-600') }}">
                                    {{ $i }}
                                </div>
                                @if($i < $totalSteps)
                                    <div class="h-1 w-8 {{ $i < $currentStep ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>

                {{-- titulo modal --}}
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Creación de Profesor</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">No olvides los detalles importantes.</p>
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

                {{-- paso #1 --}}
                @if ($currentStep == 1)
                <div class="space-y-4">
                    <!-- Nombre Completo -->
                    <div>
                        <label for="nombre_completo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre Completo</label>
                        <input type="text" id="nombre_completo" wire:model.defer="nombre_completo"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Nombres y Apellidos">
                        @error('nombre_completo')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Documento -->
                    <div>
                        <label for="tipo_documento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de Documento</label>
                        <select id="tipo_documento" wire:model.defer="tipo_documento"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">Selecciona un tipo</option>
                            <option value="RC">Registro Civil (RC)</option>
                            <option value="TI">Tarjeta de Identidad (TI)</option>
                            <option value="CC">Cédula de Ciudadanía (CC)</option>
                            <option value="TE">Tarjeta de Extranjería (TE)</option>
                            <option value="CE">Cédula de Extranjería (CE)</option>
                            <option value="NIT">Número de Identificación Tributaria (NIT)</option>
                            <option value="PP">Pasaporte (PP)</option>
                            <option value="PEP">Permiso Especial de Permanencia (PEP)</option>
                            <option value="DIE">Documento de Identificación Extranjero (DIE)</option>
                        </select>
                        @error('tipo_documento')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Número de Documento -->
                    <div>
                        <label for="documento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número de Documento</label>
                        <input type="text" id="documento" wire:model.defer="documento"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Ej: 123456789">
                        @error('documento')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- paso #2 --}}
                @elseif ($currentStep == 2)
                <div class="space-y-4">
                    <!-- Correo -->
                    <div>
                        <label for="correo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo Electrónico</label>
                        <input type="email" id="correo" wire:model.defer="correo"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Ej: profesor@ejemplo.com">
                        @error('correo')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono</label>
                        <input type="tel" id="telefono" wire:model.defer="telefono"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Ej: 3001234567">
                        @error('telefono')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Título Académico -->
                    <div>
                        <label for="titulo_academico" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Título Académico</label>
                        <input type="text" id="titulo_academico" wire:model.defer="titulo_academico"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Ej: Licenciado en Matemáticas">
                        @error('titulo_academico')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- paso #3 --}}
                @elseif ($currentStep == 3)
                <div class="space-y-4">
                    <!-- Colegio de Afiliación -->
                    <div>
                        <label for="colegio_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Colegio de Afiliación</label>
                        <select id="colegio_id" wire:model.defer="colegio_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="{{ $colegio->id }}">{{ $colegio->nombre }}</option>
                        </select>
                        @error('colegio_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sede de Afiliación -->
                    <div>
                        <label for="sede_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sede de Afiliación</label>
                        <select id="sede_id" wire:model.defer="sede_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @if (!empty($colegio->sedes))
                                <option value="">Sede principal</option>
                                @foreach ($colegio->sedes as $sede)
                                    <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                                @endforeach
                            @else
                                <option value="">Sin Sedes</option>
                            @endif
                        </select>
                        @error('sede_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @endif

                {{-- footer modal --}}
                <div class="flex pt-4">
                    <div class="flex-1"></div>
                    <div class="flex space-x-3">
                        @if ($currentStep > 1)
                            <button type="button" wire:click="prevStep()"
                                    class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 cursor-pointer">
                                Atrás
                            </button>
                        @endif

                        @if ($currentStep < $totalSteps)
                            <button type="button" wire:click="nextStep()"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 cursor-pointer">
                                Siguiente
                            </button>
                        @else
                            <button type="button" wire:click="crearProfesor()"
                                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 cursor-pointer">
                                Crear Profesor
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </flux:modal>

        {{-- modal de Edicion --}}
        <flux:modal name="editar-profesor" wire:model="modalEdicion" class="md:w-96 lg:w-10/12">

            <div class="space-y-6">
                <!-- Progress indicator -->
                <div class="flex justify-between items-center mb-6">
                    @for($i = 1; $i <= $totalStepsEdicion; $i++)
                        <div class="flex-1 flex items-center">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center
                                    {{ $i < $currentStepEdicion ? 'bg-green-500 text-white' :
                                    ($i == $currentStepEdicion ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-600') }}">
                                    {{ $i }}
                                </div>
                                @if($i < $totalStepsEdicion)
                                    <div class="h-1 w-8 {{ $i < $currentStepEdicion ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>

                {{-- titulo modal --}}
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Edicion de Profesor</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">No olvides los detalles importantes.</p>
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

                {{-- paso #1 --}}
                @if ($currentStepEdicion == 1)
                <div class="space-y-4">
                    <!-- Nombre Completo -->
                    <div>
                        <label for="nombre_completo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre Completo</label>
                        <input type="text" id="nombre_completo" wire:model.defer="nombre_completoEdicion"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Nombres y Apellidos">
                        @error('nombre_completoEdicion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Documento -->
                    <div>
                        <label for="tipo_documento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de Documento</label>
                        <select id="tipo_documento" wire:model.defer="tipo_documentoEdicion"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">Selecciona un tipo</option>
                            <option value="RC">Registro Civil (RC)</option>
                            <option value="TI">Tarjeta de Identidad (TI)</option>
                            <option value="CC">Cédula de Ciudadanía (CC)</option>
                            <option value="TE">Tarjeta de Extranjería (TE)</option>
                            <option value="CE">Cédula de Extranjería (CE)</option>
                            <option value="NIT">Número de Identificación Tributaria (NIT)</option>
                            <option value="PP">Pasaporte (PP)</option>
                            <option value="PEP">Permiso Especial de Permanencia (PEP)</option>
                            <option value="DIE">Documento de Identificación Extranjero (DIE)</option>
                        </select>
                        @error('tipo_documentoEdicion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Número de Documento -->
                    <div>
                        <label for="documento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número de Documento</label>
                        <input type="text" id="documento" wire:model.defer="documentoEdicion"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Ej: 123456789">
                        @error('documentoEdicion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- paso #2 --}}
                @elseif ($currentStepEdicion == 2)
                <div class="space-y-4">
                    <!-- Correo -->
                    <div>
                        <label for="correo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo Electrónico</label>
                        <input type="email" id="correo" wire:model.defer="correoEdicion"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Ej: profesor@ejemplo.com">
                        @error('correoEdicion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono</label>
                        <input type="tel" id="telefono" wire:model.defer="telefonoEdicion"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Ej: 3001234567">
                        @error('telefonoEdicion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Título Académico -->
                    <div>
                        <label for="titulo_academico" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Título Académico</label>
                        <input type="text" id="titulo_academico" wire:model.defer="titulo_academicoEdicion"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Ej: Licenciado en Matemáticas">
                        @error('titulo_academicoEdicion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- paso #3 --}}
                @elseif ($currentStepEdicion == 3)
                <div class="space-y-4">
                    <!-- Colegio de Afiliación -->
                    <div>
                        <label for="colegio_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Colegio de Afiliación</label>
                        <select id="colegio_id" wire:model.defer="colegio_idEdicion"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="{{ $colegio->id }}">{{ $colegio->nombre }}</option>
                        </select>
                        @error('colegio_idEdicion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sede de Afiliación -->
                    <div>
                        <label for="sede_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sede de Afiliación</label>
                        <select id="sede_id" wire:model.defer="sede_idEdicion"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @if (!empty($colegio->sedes))
                                <option value="{{ null }}">Sede principal</option>
                                @foreach ($colegio->sedes as $sede)
                                    <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                                @endforeach
                            @else
                                <option value="{{ null }}">Sin Sedes</option>
                            @endif
                        </select>
                        @error('sede_idEdicion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @endif

                {{-- footer modal --}}
                <div class="flex pt-4">
                    <div class="flex-1"></div>
                    <div class="flex space-x-3">
                        @if ($currentStepEdicion > 1)
                            <button type="button" wire:click="prevStepEdicion()"
                                    class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 cursor-pointer">
                                Atrás
                            </button>
                        @endif

                        @if ($currentStepEdicion < $totalStepsEdicion)
                            <button type="button" wire:click="nextStepEdicion()"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 cursor-pointer">
                                Siguiente
                            </button>
                        @else
                            <button type="button" wire:click="editarProfesor()"
                                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 cursor-pointer">
                                Editar Profesor
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </flux:modal>

{{-- script para escuchar alertas --}}
<script>
        Livewire.on('confirmarEliminarProfesor', (id) => {
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
                    Livewire.dispatch('eliminarAsignacionAsignaturaGrado', id);
                }
            });
        });
</script>

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
</script>
    @endpush

</div>
