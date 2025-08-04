<div class="space-y-6">
    {{-- Migas de pan --}}
    <div class="flex items-center justify-between">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('docente-inicio') }}">
                <a class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-white">
                    <svg class="h-5 w-5 inline-block mr-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Inicio
                </a>
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('docente-horarios') }}">Horarios</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    {{-- Título --}}
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Horario del Docente</h2>

    @php
        use Carbon\Carbon;

        $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'];
        $horaInicio = Carbon::createFromTime(6, 0);
        $horaFin = Carbon::createFromTime(14, 30);
        $intervalo = 30;
        $horas = collect();

        while ($horaInicio < $horaFin) {
            $horas->push($horaInicio->format('H:i'));
            $horaInicio->addMinutes($intervalo);
        }

        $horarioPorDia = collect($horario)->groupBy('dia');
        $ocupado = [];
    @endphp
    @if ($horario->isEmpty())
        <div class="text-red-500 font-semibold">No hay horarios registrados para este docente.</div>
    @endif

    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl">
        <table class="min-w-full text-sm text-gray-800 dark:text-gray-100 bg-white dark:bg-neutral-900">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-800 text-left">
                    <th class="p-3 border-r border-gray-300 dark:border-gray-700">Hora</th>
                    @foreach ($dias as $dia)
                        <th class="p-3 border-r border-gray-300 dark:border-gray-700 text-center">{{ $dia }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($horas as $hora)
                    <tr class="border-t border-gray-200 dark:border-gray-700">
                        <td class="p-3 border-r border-gray-300 dark:border-gray-700 font-semibold">{{ $hora }}</td>

                        @foreach ($dias as $dia)
                            @php
                                if (isset($ocupado[$dia][$hora])) continue;

                                $clases = $horarioPorDia[$dia] ?? collect();

                                $evento = $clases->first(function ($item) use ($hora) {
                                    return Carbon::parse($item->hora_inicio)->format('H:i') === $hora;
                                });

                                if ($evento) {
                                    $inicio = Carbon::parse($evento->hora_inicio);
                                    $fin = Carbon::parse($evento->hora_fin);
                                    $duracion = $inicio->diffInMinutes($fin);
                                    $rowspan = ($duracion / $intervalo) + 1;

                                    $horaTemp = Carbon::parse($hora)->copy();
                                    for ($i = 1; $i < $rowspan; $i++) {
                                        $horaTemp->addMinutes($intervalo);
                                        $ocupado[$dia][$horaTemp->format('H:i')] = true;
                                    }
                                }
                            @endphp
                            @if ($evento)
                                <td class="p-3 text-center border-r border-gray-300 dark:border-gray-700 align-top bg-blue-200 dark:bg-blue-900/30" rowspan="{{ $rowspan }}">
                                    <div class="text-blue-900 dark:text-blue-300">
                                        <div class="text-xs font-bold">{{ $evento->asignatura->nombre }}</div>
                                        <div class="text-xs">{{ $evento->grupo->nombre }}</div>
                                        <div class="text-xs">{{ $evento->grupo->grado->nombre }}</div>
                                        <div class="text-xs">{{ $inicio->format('H:i') }} - {{ $fin->format('H:i') }}</div>
                                    </div>
                                </td>
                            @elseif (!isset($ocupado[$dia][$hora]))
                                <td class="p-3 text-center border-r border-gray-300 dark:border-gray-700"></td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
