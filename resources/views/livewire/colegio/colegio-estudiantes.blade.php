<div>
    {{-- Migajas de pan --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('colegio-estudiantes') }}">Estudiantes</flux:breadcrumbs.item>
        @isset($colegioId)
        <flux:breadcrumbs.item>{{ $colegioId->nombre }}</flux:breadcrumbs.item>
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
            <select wire:model.live="paginacion"
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
                placeholder="Buscar Estudiante"
                class="w-[80%] h-12 px-4 rounded-lg border border-gray-300 bg-white text-gray-700
                        dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:placeholder-gray-400
                        focus:outline-none focus:ring-2 focus:ring-blue-500">

            <!-- Botón (10%) -->
            <flux:modal.trigger wire:click="$set('modalCreacion',true)" name="crear-profesor">
                <button class="w-[10%] h-12 bg-blue-600 text-white rounded-lg text-sm
                            hover:bg-blue-700 transition cursor-pointer
                            dark:bg-blue-700 dark:hover:bg-blue-800">
                    Crear Estudiante
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
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('id')">id @if($sortField === 'id')
                                @if($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif</th>
                        {{-- <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('colegio_id')">colegio</th> --}}
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('sede_id')">sede</th>
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('nombre_completo')">nombre completo</th>
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('tipo_documento')">tipo documento</th>
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('documento')">documento</th>
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('fecha_nacimiento')">fecha nacimiento</th>
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('genero')">género</th>
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('grupo_sanguineo')">grupo sanguíneo</th>
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('eps')">EPS</th>
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('sisben')">Sisbén</th>
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('poblacion_vulnerable')">población vulnerable</th>
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('discapacidad')">discapacidad</th>
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('direccion')">dirección</th>
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('telefono')">teléfono</th>
                        <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('user_id')">usuario</th>
                        <th class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($estudiantes))
                        @foreach ($estudiantes as $estudiante)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $estudiante->id }}</th>
                                {{-- <td class="px-6 py-4">{{ $estudiante->colegio->nombre }}</td> --}}
                                <td class="px-6 py-4">{{ $estudiante->sede ? $estudiante->sede->nombre : 'SEDE PRINCIPAL' }}</td>
                                <td class="px-6 py-4">{{ $estudiante->nombre_completo }}</td>
                                <td class="px-6 py-4">{{ $estudiante->tipo_documento }}</td>
                                <td class="px-6 py-4">{{ $estudiante->documento }}</td>
                                <td class="px-6 py-4">{{ $estudiante->fecha_nacimiento }}</td>
                                <td class="px-6 py-4">{{ $estudiante->genero }}</td>
                                <td class="px-6 py-4">{{ $estudiante->grupo_sanguineo }}</td>
                                <td class="px-6 py-4">{{ $estudiante->eps ?: 'Sin Eps' }}</td>
                                <td class="px-6 py-4">{{ $estudiante->sisben }}</td>
                                <td class="px-6 py-4">{{ $estudiante->poblacion_vulnerable ?: 'No Aplica' }}</td>
                                <td class="px-6 py-4">{{ $estudiante->discapacidad ?: 'Sin Discapacidad' }}</td>
                                <td class="px-6 py-4">{{ $estudiante->direccion }}</td>
                                <td class="px-6 py-4">{{ $estudiante->telefono }}</td>
                                <td class="px-6 py-4">{{ $estudiante->usuario->email }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button wire:click="cargarEstudiante({{ $estudiante->id }})" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs dark:bg-blue-600 dark:hover:bg-blue-700 cursor-pointer w-full m-1">
                                        Editar
                                    </button>
                                    <button wire:click="$dispatch('confirmarEliminarEstudiante', { id: {{ $estudiante->id }} })" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-xs dark:bg-red-600 dark:hover:bg-red-700 cursor-pointer w-full m-1">
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
        @if ($estudiantes->hasPages())
            {{ $estudiantes->links() }}
        @endif

    </div>

        {{-- Modal de Creacion Estudiante --}}
<flux:modal name="crear-estudiante" wire:model="modalCreacion" class="md:w-96 lg:w-10/12">
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

        {{-- Titulo Modal --}}
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Creación de Estudiante</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-300">Complete toda la información requerida.</p>
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
        @if ($currentStep == 1)
            <div class="space-y-4">
                <!-- Nombre Completo -->
                <div>
                    <label for="nombre_completo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre Completo*</label>
                    <input type="text" id="nombre_completo" wire:model.defer="nombre_completo"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Nombres y Apellidos">
                    @error('nombre_completo')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo de Documento -->
                <div>
                    <label for="tipo_documento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de Documento*</label>
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
                    <label for="documento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número de Documento*</label>
                    <input type="text" id="documento" wire:model.defer="documento"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Ej: 123456789">
                    @error('documento')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha de Nacimiento -->
                <div>
                    <label for="fecha_nacimiento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha de Nacimiento*</label>
                    <input type="date" id="fecha_nacimiento" wire:model.defer="fecha_nacimiento"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    @error('fecha_nacimiento')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Género -->
                <div>
                    <label for="genero" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Género</label>
                    <select id="genero" wire:model.defer="genero"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccione</option>
                        <option value="masculino">Masculino</option>
                        <option value="femenino">Femenino</option>
                        <option value="otro">Otro</option>
                        <option value="prefiero_no_decir">Prefiero no decir</option>
                    </select>
                    @error('genero')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

        {{-- Paso #2: Información de Contacto --}}
        @elseif ($currentStep == 2)
            <div class="space-y-4">
                <!-- Dirección -->
                <div>
                    <label for="direccion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dirección</label>
                    <input type="text" id="direccion" wire:model.defer="direccion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Dirección de residencia">
                    @error('direccion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono*</label>
                    <input type="tel" id="telefono" wire:model.defer="telefono"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Ej: 3001234567">
                    @error('telefono')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Correo -->
                <div>
                    <label for="correo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo Electrónico</label>
                    <input type="email" id="correo" wire:model.defer="correo"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Ej: estudiante@ejemplo.com">
                    @error('correo')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

        {{-- Paso #3: Información de Salud --}}
        @elseif ($currentStep == 3)
            <div class="space-y-4">
                <!-- Grupo Sanguíneo -->
                <div>
                    <label for="grupo_sanguineo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Grupo Sanguíneo</label>
                    <select id="grupo_sanguineo" wire:model.defer="grupo_sanguineo"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccione</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                    @error('grupo_sanguineo')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- EPS -->
                <div>
                    <label for="eps" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">EPS</label>
                    <select id="eps" wire:model.defer="eps"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Selecciona una EPS</option>
                        <option value="COOMEVA">COOMEVA</option>
                        <option value="CAFESALUD">CAFESALUD</option>
                        <option value="COMPENSAR">COMPENSAR</option>
                        <option value="SALUDTOTAL">SALUDTOTAL</option>
                        <option value="SANCOR">SANCOR</option>
                        <option value="EPS SANITAS">EPS SANITAS</option>
                        <option value="EPS SURAMERICANA">EPS SURAMERICANA</option>
                        <option value="EMSSANAR">EMSSANAR</option>
                        <option value="EPS SALUD PUBLICA">EPS SALUD PUBLICA</option>
                        <option value="CRUZ BLANCA">CRUZ BLANCA</option>
                        <option value="">Sin eps</option>
                    </select>
                    @error('eps')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Clasificación del Sisbén -->
                <div>
                    <label for="sisben" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Clasificación del Sisbén</label>
                    <select id="sisben" wire:model.defer="sisben"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccione una opción</option>
                        <option value="A1">A1 - Pobreza extrema</option>
                        <option value="A2">A2 - Pobreza extrema</option>
                        <option value="A3">A3 - Pobreza extrema</option>
                        <option value="A4">A4 - Pobreza extrema</option>
                        <option value="A5">A5 - Pobreza extrema</option>
                        <option value="B1">B1 - Pobreza moderada</option>
                        <option value="B2">B2 - Pobreza moderada</option>
                        <option value="B3">B3 - Pobreza moderada</option>
                        <option value="B4">B4 - Pobreza moderada</option>
                        <option value="B5">B5 - Pobreza moderada</option>
                        <option value="B6">B6 - Pobreza moderada</option>
                        <option value="B7">B7 - Pobreza moderada</option>
                        <option value="C1">C1 - Vulnerabilidad</option>
                        <option value="C2">C2 - Vulnerabilidad</option>
                        <option value="C3">C3 - Vulnerabilidad</option>
                        <option value="C4">C4 - Vulnerabilidad</option>
                        <option value="C5">C5 - Vulnerabilidad</option>
                        <option value="C6">C6 - Vulnerabilidad</option>
                        <option value="C7">C7 - Vulnerabilidad</option>
                        <option value="C8">C8 - Vulnerabilidad</option>
                        <option value="C9">C9 - Vulnerabilidad</option>
                        <option value="C10">C10 - Vulnerabilidad</option>
                        <option value="C11">C11 - Vulnerabilidad</option>
                        <option value="C12">C12 - Vulnerabilidad</option>
                        <option value="C13">C13 - Vulnerabilidad</option>
                        <option value="C14">C14 - Vulnerabilidad</option>
                        <option value="C15">C15 - Vulnerabilidad</option>
                        <option value="C16">C16 - Vulnerabilidad</option>
                        <option value="C17">C17 - Vulnerabilidad</option>
                        <option value="C18">C18 - Vulnerabilidad</option>
                        <option value="D1">D1 - No pobre ni vulnerable</option>
                        <option value="D2">D2 - No pobre ni vulnerable</option>
                        <option value="D3">D3 - No pobre ni vulnerable</option>
                        <option value="D4">D4 - No pobre ni vulnerable</option>
                        <option value="D5">D5 - No pobre ni vulnerable</option>
                        <option value="D6">D6 - No pobre ni vulnerable</option>
                        <option value="D7">D7 - No pobre ni vulnerable</option>
                        <option value="D8">D8 - No pobre ni vulnerable</option>
                        <option value="D9">D9 - No pobre ni vulnerable</option>
                        <option value="D10">D10 - No pobre ni vulnerable</option>
                        <option value="D11">D11 - No pobre ni vulnerable</option>
                        <option value="D12">D12 - No pobre ni vulnerable</option>
                        <option value="D13">D13 - No pobre ni vulnerable</option>
                        <option value="D14">D14 - No pobre ni vulnerable</option>
                        <option value="D15">D15 - No pobre ni vulnerable</option>
                        <option value="D16">D16 - No pobre ni vulnerable</option>
                        <option value="D17">D17 - No pobre ni vulnerable</option>
                        <option value="D18">D18 - No pobre ni vulnerable</option>
                        <option value="D19">D19 - No pobre ni vulnerable</option>
                        <option value="D20">D20 - No pobre ni vulnerable</option>
                        <option value="D21">D21 - No pobre ni vulnerable</option>
                    </select>
                    @error('sisben')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Población Vulnerable -->
                <div>
                    <label for="poblacion_vulnerable" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Población Vulnerable</label>
                    <select id="poblacion_vulnerable" wire:model.defer="poblacion_vulnerable"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccione una opción</option>
                        <option value="Pobreza Extrema">Pobreza Extrema</option>
                        <option value="Pobreza Moderada">Pobreza Moderada</option>
                        <option value="Desplazados por la Violencia">Desplazados por la Violencia</option>
                        <option value="Niños, Niñas y Adolescentes">Niños, Niñas y Adolescentes</option>
                        <option value="Personas con Discapacidad">Personas con Discapacidad</option>
                        <option value="Comunidades Indígenas">Comunidades Indígenas</option>
                        <option value="Afrocolombianos">Afrocolombianos</option>
                        <option value="Víctimas del Conflicto Armado">Víctimas del Conflicto Armado</option>
                        <option value="Personas LGTBI">Personas LGTBI</option>
                        <option value="Víctimas de Desastres Naturales">Víctimas de Desastres Naturales</option>
                        <option value="Mujeres Víctimas de Violencia de Género">Mujeres Víctimas de Violencia de Género</option>
                        <option value="Adultos Mayores">Adultos Mayores</option>
                        <option value="Otros">Otros</option>
                        <option value="">No reporta población vulnerable</option>
                    </select>
                    @error('poblacion_vulnerable')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Discapacidad -->
                <div>
                    <label for="discapacidad" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Discapacidad</label>
                    <select id="discapacidad" wire:model.defer="discapacidad"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccione una discapacidad</option>
                        <option value="Visual">Discapacidad Visual</option>
                        <option value="Auditiva">Discapacidad Auditiva</option>
                        <option value="Física / Motora">Discapacidad Física / Motora</option>
                        <option value="Intelectual">Discapacidad Intelectual</option>
                        <option value="Psicosocial">Discapacidad Psicosocial</option>
                        <option value="Múltiple">Discapacidad Múltiple</option>
                        <option value="">Sin Discapacidad</option>
                    </select>
                    @error('discapacidad')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

        {{-- Paso #4: Información Académica --}}
        @elseif ($currentStep == 4)
            <div class="space-y-4">
                <!-- Colegio -->
                <div>
                    <label for="colegio_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Colegio de Afiliación*</label>
                    <select id="colegio_id" wire:model.defer="colegio_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        @if ($colegioId->colegio)
                        <option value="{{ $colegioId->colegio->id }}">{{ $colegioId->colegio->nombre }}</option>
                        @else
                        <option value="{{ $colegioId->id }}">{{ $colegioId->nombre }}</option>
                        @endif
                    </select>
                    @error('colegio_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sede -->
                <div>
                    <label for="sede_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sede de Afiliación</label>
                    <select id="sede_id" wire:model.defer="sede_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        {{-- es una sede --}}
                        @if ($colegioId->colegio)
                            <option value="{{ $colegioId->id }}">{{ $colegioId->nombre }}</option>
                        @elseif($colegioId)
                            <option value="">Sede principal</option>
                            @foreach ($colegioId->sedes as $sede)
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

        {{-- Footer modal --}}
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
                    <button type="button" wire:click="crearEstudiante()"
                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 cursor-pointer">
                        Crear Estudiante
                    </button>
                @endif
            </div>
        </div>
    </div>
</flux:modal>

{{-- Modal de Edicion estudiante --}}
<flux:modal name="editar-estudiante" wire:model="modalEdicion" class="md:w-96 lg:w-10/12">
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
                        @if($i < $totalSteps)
                            <div class="h-1 w-8 {{ $i < $currentStepEdicion ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                        @endif
                    </div>
                </div>
            @endfor
        </div>

        {{-- Titulo Modal --}}
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Edicion de Estudiante</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-300">Edite toda la información requerida.</p>
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
        @if ($currentStepEdicion == 1)
            <div class="space-y-4">
                <!-- Nombre Completo -->
                <div>
                    <label for="nombre_completoEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre Completo*</label>
                    <input type="text" id="nombre_completoEdicion" wire:model.defer="nombre_completoEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Nombres y Apellidos">
                    @error('nombre_completoEdicion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo de Documento -->
                <div>
                    <label for="tipo_documentoEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de Documento*</label>
                    <select id="tipo_documentoEdicion" wire:model.defer="tipo_documentoEdicion"
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
                    <label for="documentoEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número de Documento*</label>
                    <input type="text" id="documentoEdicion" wire:model.defer="documentoEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Ej: 123456789">
                    @error('documentoEdicion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha de Nacimiento -->
                <div>
                    <label for="fecha_nacimientoEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha de Nacimiento*</label>
                    <input type="date" id="fecha_nacimientoEdicion" wire:model.defer="fecha_nacimientoEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    @error('fecha_nacimientoEdicion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Género -->
                <div>
                    <label for="generoEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Género</label>
                    <select id="generoEdicion" wire:model.defer="generoEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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

        {{-- Paso #2: Información de Contacto --}}
        @elseif ($currentStepEdicion == 2)
            <div class="space-y-4">
                <!-- Dirección -->
                <div>
                    <label for="direccionEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dirección</label>
                    <input type="text" id="direccionEdicion" wire:model.defer="direccionEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Dirección de residencia">
                    @error('direccionEdicion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefonoEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono*</label>
                    <input type="tel" id="telefonoEdicion" wire:model.defer="telefonoEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Ej: 3001234567">
                    @error('telefonoEdicion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Correo -->
                <div>
                    <label for="correoEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo Electrónico</label>
                    <input type="email" id="correoEdicion" wire:model.defer="correoEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Ej: estudiante@ejemplo.com">
                    @error('correoEdicion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

        {{-- Paso #3: Información de Salud --}}
        @elseif ($currentStepEdicion == 3)
            <div class="space-y-4">
                <!-- Grupo Sanguíneo -->
                <div>
                    <label for="grupo_sanguineoEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Grupo Sanguíneo</label>
                    <select id="grupo_sanguineoEdicion" wire:model.defer="grupo_sanguineoEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccione</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                    @error('grupo_sanguineoEdicion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- EPS -->
                <div>
                    <label for="epsEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">EPS</label>
                    <select id="epsEdicion" wire:model.defer="epsEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Selecciona una EPS</option>
                        <option value="COOMEVA">COOMEVA</option>
                        <option value="CAFESALUD">CAFESALUD</option>
                        <option value="COMPENSAR">COMPENSAR</option>
                        <option value="SALUDTOTAL">SALUDTOTAL</option>
                        <option value="SANCOR">SANCOR</option>
                        <option value="EPS SANITAS">EPS SANITAS</option>
                        <option value="EPS SURAMERICANA">EPS SURAMERICANA</option>
                        <option value="EMSSANAR">EMSSANAR</option>
                        <option value="EPS SALUD PUBLICA">EPS SALUD PUBLICA</option>
                        <option value="CRUZ BLANCA">CRUZ BLANCA</option>
                        <option value="">Sin eps</option>
                    </select>
                    @error('epsEdicion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Clasificación del Sisbén -->
                <div>
                    <label for="sisbenEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Clasificación del Sisbén</label>
                    <select id="sisbenEdicion" wire:model.defer="sisbenEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccione una opción</option>
                        <option value="A1">A1 - Pobreza extrema</option>
                        <option value="A2">A2 - Pobreza extrema</option>
                        <option value="A3">A3 - Pobreza extrema</option>
                        <option value="A4">A4 - Pobreza extrema</option>
                        <option value="A5">A5 - Pobreza extrema</option>
                        <option value="B1">B1 - Pobreza moderada</option>
                        <option value="B2">B2 - Pobreza moderada</option>
                        <option value="B3">B3 - Pobreza moderada</option>
                        <option value="B4">B4 - Pobreza moderada</option>
                        <option value="B5">B5 - Pobreza moderada</option>
                        <option value="B6">B6 - Pobreza moderada</option>
                        <option value="B7">B7 - Pobreza moderada</option>
                        <option value="C1">C1 - Vulnerabilidad</option>
                        <option value="C2">C2 - Vulnerabilidad</option>
                        <option value="C3">C3 - Vulnerabilidad</option>
                        <option value="C4">C4 - Vulnerabilidad</option>
                        <option value="C5">C5 - Vulnerabilidad</option>
                        <option value="C6">C6 - Vulnerabilidad</option>
                        <option value="C7">C7 - Vulnerabilidad</option>
                        <option value="C8">C8 - Vulnerabilidad</option>
                        <option value="C9">C9 - Vulnerabilidad</option>
                        <option value="C10">C10 - Vulnerabilidad</option>
                        <option value="C11">C11 - Vulnerabilidad</option>
                        <option value="C12">C12 - Vulnerabilidad</option>
                        <option value="C13">C13 - Vulnerabilidad</option>
                        <option value="C14">C14 - Vulnerabilidad</option>
                        <option value="C15">C15 - Vulnerabilidad</option>
                        <option value="C16">C16 - Vulnerabilidad</option>
                        <option value="C17">C17 - Vulnerabilidad</option>
                        <option value="C18">C18 - Vulnerabilidad</option>
                        <option value="D1">D1 - No pobre ni vulnerable</option>
                        <option value="D2">D2 - No pobre ni vulnerable</option>
                        <option value="D3">D3 - No pobre ni vulnerable</option>
                        <option value="D4">D4 - No pobre ni vulnerable</option>
                        <option value="D5">D5 - No pobre ni vulnerable</option>
                        <option value="D6">D6 - No pobre ni vulnerable</option>
                        <option value="D7">D7 - No pobre ni vulnerable</option>
                        <option value="D8">D8 - No pobre ni vulnerable</option>
                        <option value="D9">D9 - No pobre ni vulnerable</option>
                        <option value="D10">D10 - No pobre ni vulnerable</option>
                        <option value="D11">D11 - No pobre ni vulnerable</option>
                        <option value="D12">D12 - No pobre ni vulnerable</option>
                        <option value="D13">D13 - No pobre ni vulnerable</option>
                        <option value="D14">D14 - No pobre ni vulnerable</option>
                        <option value="D15">D15 - No pobre ni vulnerable</option>
                        <option value="D16">D16 - No pobre ni vulnerable</option>
                        <option value="D17">D17 - No pobre ni vulnerable</option>
                        <option value="D18">D18 - No pobre ni vulnerable</option>
                        <option value="D19">D19 - No pobre ni vulnerable</option>
                        <option value="D20">D20 - No pobre ni vulnerable</option>
                        <option value="D21">D21 - No pobre ni vulnerable</option>
                    </select>
                    @error('sisbenEdicion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Población Vulnerable -->
                <div>
                    <label for="poblacion_vulnerableEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Población Vulnerable</label>
                    <select id="poblacion_vulnerableEdicion" wire:model.defer="poblacion_vulnerableEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccione una opción</option>
                        <option value="Pobreza Extrema">Pobreza Extrema</option>
                        <option value="Pobreza Moderada">Pobreza Moderada</option>
                        <option value="Desplazados por la Violencia">Desplazados por la Violencia</option>
                        <option value="Niños, Niñas y Adolescentes">Niños, Niñas y Adolescentes</option>
                        <option value="Personas con Discapacidad">Personas con Discapacidad</option>
                        <option value="Comunidades Indígenas">Comunidades Indígenas</option>
                        <option value="Afrocolombianos">Afrocolombianos</option>
                        <option value="Víctimas del Conflicto Armado">Víctimas del Conflicto Armado</option>
                        <option value="Personas LGTBI">Personas LGTBI</option>
                        <option value="Víctimas de Desastres Naturales">Víctimas de Desastres Naturales</option>
                        <option value="Mujeres Víctimas de Violencia de Género">Mujeres Víctimas de Violencia de Género</option>
                        <option value="Adultos Mayores">Adultos Mayores</option>
                        <option value="Otros">Otros</option>
                        <option value="">No reporta población vulnerable</option>
                    </select>
                    @error('poblacion_vulnerableEdicion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Discapacidad -->
                <div>
                    <label for="discapacidadEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Discapacidad</label>
                    <select id="discapacidadEdicion" wire:model.defer="discapacidadEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccione una discapacidad</option>
                        <option value="Visual">Discapacidad Visual</option>
                        <option value="Auditiva">Discapacidad Auditiva</option>
                        <option value="Física / Motora">Discapacidad Física / Motora</option>
                        <option value="Intelectual">Discapacidad Intelectual</option>
                        <option value="Psicosocial">Discapacidad Psicosocial</option>
                        <option value="Múltiple">Discapacidad Múltiple</option>
                        <option value="">Sin Discapacidad</option>
                    </select>
                    @error('discapacidadEdicion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

        {{-- Paso #4: Información Académica --}}
        @elseif ($currentStepEdicion == 4)
            <div class="space-y-4">
                <!-- Colegio -->
                <div>
                    <label for="colegio_idEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Colegio de Afiliación*</label>
                    <select id="colegio_idEdicion" wire:model.defer="colegio_idEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="{{ $colegioId->id }}">{{ $colegioId->nombre }}</option>
                    </select>
                    @error('colegio_idEdicion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sede -->
                <div>
                    <label for="sede_idEdicion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sede de Afiliación</label>
                    <select id="sede_idEdicion" wire:model.defer="sede_idEdicion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        @if (!empty($colegioId->sedes))
                            <option value="">Sede principal</option>
                            @foreach ($colegioId->sedes as $sede)
                                <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                            @endforeach
                        @else
                            <option value="">Sin Sedes</option>
                        @endif
                    </select>
                    @error('sede_idEdicion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        @endif

        {{-- Footer modal --}}
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
                    <button type="button" wire:click="editarEstudiante()"
                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 cursor-pointer">
                        Editar Estudiante
                    </button>
                @endif
            </div>
        </div>
    </div>
</flux:modal>
{{-- script para escuchar alertas --}}


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

     Livewire.on('confirmarEliminarEstudiante', (id) => {
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
                    Livewire.dispatch('EliminarEstudiante', id);
                }
            });
    });
</script>
    @endpush

</div>
