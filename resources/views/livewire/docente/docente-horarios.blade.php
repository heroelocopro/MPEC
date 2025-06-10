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
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        $horas = collect($horario)->pluck('hora_inicio')->merge($horario->pluck('hora_fin'))->unique()->sort()->values();

        // Agrupa por día
        $horarioPorDia = collect($horario)->groupBy('dia');
    @endphp
    {{-- Tabla de horarios --}}
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
                        <td class="p-3 border-r border-gray-300 dark:border-gray-700 font-semibold">{{ \Carbon\Carbon::parse($hora)->format('H:i') }}</td>
                        @foreach ($dias as $dia)
                            @php
                                $clases = $horarioPorDia[$dia] ?? collect();
                                $evento = $clases->first(function ($item) use ($hora) {
                                    return $item->hora_inicio === $hora;
                                });
                            @endphp
                            <td class="p-3 text-center border-r border-gray-300 dark:border-gray-700 align-top">
                                @if ($evento)
                                    <div class="bg-blue-100 dark:bg-blue-900/30 text-blue-900 dark:text-blue-300 p-2 rounded-md shadow-sm">
                                        <div class="text-xs font-bold">{{ $evento->asignatura->nombre }}</div>
                                        <div class="text-xs">{{ $evento->grupo->nombre }}</div>
                                        <div class="text-xs">{{ $evento->grupo->grado->nombre }}</div>
                                        <div class="text-xs">{{ \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($evento->hora_fin)->format('H:i') }}</div>
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
