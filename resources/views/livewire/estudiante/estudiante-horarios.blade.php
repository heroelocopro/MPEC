<div>
    {{-- Notificaciones fijas --}}
    <div class="fixed top-4 right-4 z-50 cursor-pointer">
        <livewire:notificaciones />
    </div>

    {{-- Migas de pan --}}
    <div class="flex items-center justify-between mb-6">
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

    {{-- Validaciones previas --}}
    @if (!$grupo)
        <div class="bg-yellow-100 text-yellow-800 dark:bg-yellow-200 dark:text-yellow-900 p-4 rounded-lg shadow mb-4">
            No tienes un grupo asignado actualmente.
        </div>
    @elseif ($horarios->isEmpty())
        <div class="bg-blue-100 text-blue-800 dark:bg-blue-200 dark:text-blue-900 p-4 rounded-lg shadow mb-4">
            Aún no se ha cargado tu horario.
        </div>
    @elseif (empty($diasConHorario))
        <div class="bg-red-100 text-red-800 dark:bg-red-200 dark:text-red-900 p-4 rounded-lg shadow mb-4">
            No se han definido los días del horario.
        </div>
    @else
        {{-- Tabla de horario --}}
        <div class="overflow-x-auto rounded-xl shadow-lg border border-gray-300 dark:border-gray-700">
            <table class="min-w-full text-sm text-gray-800 dark:text-gray-200 border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-indigo-500 to-blue-600 text-white">
                        {{-- <th class="p-3 text-center font-semibold uppercase tracking-wide border-r border-blue-700">
                            HORA / DÍA
                        </th> --}}
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
                            {{-- <td class="text-center font-semibold p-3 border-r border-gray-300 dark:border-gray-700 whitespace-nowrap">
                                {{ $hora }}
                            </td> --}}

                            @foreach ($diasConHorario as $dia)
                                <td class="p-3 text-center align-top border-l border-gray-300 dark:border-gray-700 min-w-[140px]">
                                    @if ($dias->has($dia))
                                        @php
                                            $clase = $dias[$dia];
                                            $inicio = \Carbon\Carbon::parse($clase->hora_inicio)->format('H:i');
                                            $fin = \Carbon\Carbon::parse($clase->hora_fin)->format('H:i');
                                        @endphp
                                        <div class="text-indigo-800 dark:text-indigo-200 font-semibold truncate" title="{{ $clase->asignatura->nombre }}">
                                            {{ $clase->asignatura->nombre }}
                                        </div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400 truncate" title="{{ $clase->profesor->nombre_completo ?? '' }}">
                                            {{ $clase->profesor->nombre_completo ?? '' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            {{ $inicio }} - {{ $fin }}
                                        </div>
                                        <div class="text-xs text-gray-400 dark:text-gray-500 truncate">
                                            {{ $clase->grupo->nombre ?? '' }}
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
    @endif
</div>
