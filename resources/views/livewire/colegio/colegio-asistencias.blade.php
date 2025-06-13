<div>
    {{-- Encabezado superior --}}
    <div class="flex items-center justify-between mb-6">
        {{-- Migas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('colegio-grados') }}">Asistencias</flux:breadcrumbs.item>
                @isset($colegio)
                    <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                @endisset
            </flux:breadcrumbs>
        </div>
    </div>
    {{-- Contenedor principal de selección --}}
    <div class="flex flex-col md:flex-row gap-6">
        {{-- Selector de grados --}}
        <div class="w-full md:w-64 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Grados</h2>
            <ul class="space-y-2">
                @foreach ($grados as $grado)
                    <li>
                        <button wire:model.live="grado_id" wire:click="seleccionarGrado({{ $grado->id }})"
                            class="w-full cursor-pointer text-left px-3 py-2 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 transition
                            {{ $grado_id == $grado->id ? 'bg-blue-200 dark:bg-blue-700 text-white' : 'text-gray-700 dark:text-gray-300' }}">
                            {{ $grado->nombre }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Selector de grupos y muestra de estudiantes --}}
        <div class="flex-1 space-y-4">
            {{-- Selector de grupos si ya se seleccionó un grado --}}
            @if ($grado_id)
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
                    <label for="grupo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selecciona un grupo</label>
                    <select wire:model.live="grupo_id" id="grupo" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        <option value="">-- Selecciona --</option>
                        @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Lista de estudiantes si ya hay grupo --}}
            @if ($grupo_id && $estudiantes)
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Estudiantes del grupo</h3>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-2 text-left">#</th>
                                <th class="px-4 py-2 text-left">Nombre</th>
                                <th class="px-4 py-2 text-left">Asistencias</th>
                                <th class="px-4 py-2 text-left">Fallas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($estudiantes as $index => $est)
                                <tr>
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $est->nombre_completo }}</td>
                                    <td class="px-4 py-2">{{ count($est->asistencias) ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
