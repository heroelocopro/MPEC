<div>
    {{-- breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('docente-inicio') }}">Panel Principal</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="">Grupo</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ $grupo->nombre }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Detalles</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ $grupo->grado->nombre }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    {{-- Main --}}
    <div class="p-4 sm:p-6 lg:p-8 bg-white dark:bg-gray-900 rounded-xl shadow-md space-y-6">

        {{-- Título --}}
        <div class="flex items-center space-x-3">
            <svg class="w-6 h-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 16l-4-4 4-4m8 8l4-4-4-4M12 20v-8m0 0V4" />
            </svg>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">
                Detalles del Grupo: {{ $grupo->nombre }}
            </h2>
        </div>

        {{-- Estadísticas del grupo --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Estudiantes -->
            <div class="flex items-center p-4 bg-sky-50 dark:bg-sky-950 rounded-lg shadow-sm">
                <svg class="w-6 h-6 text-sky-600 dark:text-sky-400 mr-3" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M4 20h5m-5 0v-2a4 4 0 013-3.87M12 14a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
                <div>
                    <p class="text-gray-800 dark:text-white font-semibold">Estudiantes</p>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">{{ $estudiantes }} registrados</p>
                </div>
            </div>

            <!-- Promedio de Notas -->
            <div class="flex items-center p-4 bg-emerald-50 dark:bg-emerald-950 rounded-lg shadow-sm">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 mr-3" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 20l9-5-9-5-9 5 9 5zM12 12V4m0 0L3.4 9M12 4l8.6 5" />
                </svg>
                <div>
                    <p class="text-gray-800 dark:text-white font-semibold">Promedio Notas</p>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">{{ $promedioNotas }}</p>
                </div>
            </div>

            <!-- dias de clase -->
            <div class="flex items-center p-4 bg-indigo-50 dark:bg-indigo-950 rounded-lg shadow-sm">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 mr-3" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-gray-800 dark:text-white font-semibold">dias de clase</p>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">individual <span class="text-red-500">{{ $diasTotales }}</span> dias. Grupal <span class="text-red-500">{{ $diasTotales * $estudiantes }}</span> dias </p>
                </div>
            </div>
            <!-- Asistencias -->
            <div class="flex items-center p-4 bg-indigo-50 dark:bg-indigo-950 rounded-lg shadow-sm">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 mr-3" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-gray-800 dark:text-white font-semibold">Asistencia</p>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">asistencias actuales <span class="text-red-500">{{ $diasAsistidos }}</span> </p>
                </div>
            </div>

            <!-- Estudiantes Especiales -->
            <div class="flex items-center p-4 bg-rose-50 dark:bg-rose-950 rounded-lg shadow-sm">
                <svg class="w-6 h-6 text-rose-600 dark:text-rose-400 mr-3" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c1.657 0 3 1.343 3 3s-1.343 3-3 3m0 0c-1.657 0-3-1.343-3-3s1.343-3 3-3zm0 0v.01M12 15v2m0 4h.01" />
                </svg>
                <div>
                    <p class="text-gray-800 dark:text-white font-semibold">Necesidades Especiales</p>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">{{ $estudiantesEspeciales }} estudiantes</p>
                </div>
            </div>


        </div>
        {{-- tabla --}}
        {{-- Tabla de estudiantes --}}
<div class="p-4 sm:p-6 lg:p-8 bg-white dark:bg-gray-900 rounded-xl shadow-md space-y-6 mt-6">
    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Estudiantes del Grupo</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Asistencias</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Promedio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Condición</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($tablaEstudiantes as $index => $estudiante)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $estudiante->nombre_completo }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                            {{ count($estudiante->asistencias) }} 
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                            {{ number_format($estudiante->promedio($grupo->id), 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                            @if ($estudiante->discapacidad)
                                <span class=" {{ $estudiante->discapacidad == 'Ninguna' ? 'text-gray-500' : 'text-rose-500' }} font-semibold">Discapacidad {{ $estudiante->discapacidad }}</span>
                            @elseif ($estudiante->superdotado)
                                <span class=" {{ $estudiante->discapacidad == 'Ninguna' ? 'text-gray-500' : 'text-emerald-500' }} font-semibold">Superdotado</span>

                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button name="ver-estudiante" wire:click="verEstudiante({{ $estudiante->id }})"
                                class="text-sky-600 cursor-pointer hover:text-sky-800 dark:hover:text-sky-400"
                                title="Ver información completa">
                                👁️
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

    </div>


    {{-- modal show para los estudiantes jsjsjsj --}}

@if ($estudianteSeleccionado != null && isset($estudianteSeleccionado))
<flux:modal wire:model.live="modalVer" name="ver-estudiante" class="lg:w-7xl w-full">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Información del Estudiante</flux:heading>
            <flux:text class="mt-2 text-lg font-semibold text-gray-800 dark:text-white">
                {{ $estudianteSeleccionado->nombre_completo }}
            </flux:text>
        </div>

        {{-- Datos Básicos --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input label="Tipo de Documento" :value="$estudianteSeleccionado->tipo_documento" disabled />
            <flux:input label="Documento" :value="$estudianteSeleccionado->documento" disabled />
            <flux:input label="Fecha de Nacimiento" :value="$estudianteSeleccionado->fecha_nacimiento" disabled />
            <flux:input label="Edad" :value=" $estudianteSeleccionado->edad() " disabled />
            <flux:input label="Género" :value="$estudianteSeleccionado->genero" disabled />
        </div>

        {{-- Salud y Bienestar --}}
        <flux:heading size="sm" class="pt-4">Salud y Bienestar</flux:heading>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input label="Grupo Sanguíneo" :value="$estudianteSeleccionado->grupo_sanguineo" disabled />
            <flux:input label="EPS" :value="$estudianteSeleccionado->eps" disabled />
            <flux:input label="Discapacidad" :value="$estudianteSeleccionado->discapacidad ?? 'Ninguna'" disabled />
            <flux:input label="Población Vulnerable" :value="$estudianteSeleccionado->poblacion_vulnerable ?? 'No'" disabled />
        </div>

        {{-- Datos Socioeconómicos --}}
        <flux:heading size="sm" class="pt-4">Información Socioeconómica</flux:heading>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input label="SISBEN" :value="$estudianteSeleccionado->sisben" disabled />
            <flux:input label="Dirección" :value="$estudianteSeleccionado->direccion" disabled />
            <flux:input label="Teléfono" :value="$estudianteSeleccionado->telefono" disabled />
            <flux:input label="Correo Electrónico" :value="$estudianteSeleccionado->correo" disabled />
        </div>

        {{-- Escolaridad --}}
        <flux:heading size="sm" class="pt-4">Información Académica</flux:heading>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input label="Colegio" :value="$estudianteSeleccionado->colegio->nombre ?? 'Sin colegio'" disabled />
            <flux:input label="Sede" :value="$estudianteSeleccionado->sede->nombre ?? 'Sede Principal'" disabled />
        </div>

        {{-- Botón de cierre --}}
        <div class="flex justify-end pt-4">
            <flux:button class="cursor-pointer" type="button" wire:click="$set('modalVer', false)">Cerrar</flux:button>
        </div>
    </div>
</flux:modal>

@endif
</div>
