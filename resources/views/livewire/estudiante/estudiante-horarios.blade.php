<div>
    {{-- Notificaciones fijas a la derecha superior --}}
    <div class="fixed top-4 right-4 z-50 cursor-pointer">
        <livewire:notificaciones />
    </div>
    {{-- Parte Superior --}}
    <div class="flex items-center justify-between mb-6">
        {{-- Migas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('estudiante-horarios') }}">Horario</flux:breadcrumbs.item>
                @isset($colegio)
                <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                @endisset
                @isset($grupo)
                <flux:breadcrumbs.item>{{ $grupo->nombre }}</flux:breadcrumbs.item>
                @endisset
            </flux:breadcrumbs>
        </div>
    </div>

    {{-- Tabla del Horario --}}
    <div class="overflow-x-auto rounded-lg shadow-lg border border-gray-300 dark:border-gray-700">
        <table class="min-w-full text-sm text-gray-800 dark:text-gray-200 border-collapse">
            <thead>
                <tr class="bg-gradient-to-r from-indigo-400 to-blue-600 text-white">
                    <th class="sticky left-0 z-20 bg-gradient-to-r from-indigo-400 to-blue-600 p-3 text-left font-semibold rounded-l-lg border-r border-blue-700">Hora</th>
                    @foreach ($diasConHorario as $dia)
                        <th class="p-3 text-center font-semibold uppercase tracking-wide border-l border-blue-700">
                            {{ ucfirst($dia) }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900">
                @foreach ($horarios as $hora => $dias)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-900 transition-colors">
                        <td class="sticky left-0 z-10 bg-white dark:bg-gray-900 font-semibold p-3 border-r border-gray-300 dark:border-gray-700 whitespace-nowrap rounded-l-md">
                            {{ $hora }}
                        </td>
                        @foreach ($diasConHorario as $dia)
                            <td class="p-3 text-center align-top border-l border-gray-300 dark:border-gray-700 min-w-[120px]">
                                @if ($dias->has($dia))
                                    <div class="text-indigo-800 dark:text-indigo-200 font-semibold truncate" title="{{ $dias[$dia]->asignatura->nombre }}">
                                        {{ $dias[$dia]->asignatura->nombre }}
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 truncate" title="{{ $dias[$dia]->profesor->nombre_completo ?? '' }}">
                                        {{ $dias[$dia]->profesor->nombre_completo ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate" title="{{ $dias[$dia]->grupo->nombre ?? '' }}">
                                        {{ $dias[$dia]->grupo->nombre ?? '' }}
                                    </div>
                                @else
                                    <div class="text-gray-400 italic select-none">Libre</div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
