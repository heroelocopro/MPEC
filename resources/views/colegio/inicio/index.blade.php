<x-layouts.app :title="env('APP_NAME')">
    <div class="flex flex-col gap-6">

        {{-- Encabezado del módulo --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Panel del Colegio</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Bienvenido, aquí puedes gestionar todo tu entorno escolar.</p>
            </div>
            <div class="flex flex-wrap gap-2 cursor-pointer">
                <a class="cursor-pointer" href="{{ route('colegio-horarios') }}">
                    <x-button class="cursor-pointer">Configurar Horario</x-button>
                </a>
                <a href="{{ route('colegio-anuncios') }}">
                    <x-button class="cursor-pointer" color="secondary">Publicar Anuncio</x-button>
                </a>
            </div>
        </div>

        {{-- Estadísticas principales --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $cards = [
                    ['label' => 'Estudiantes Matriculados', 'value' => $totalEstudiantes, 'color' => 'blue', 'icon' => '👨‍🎓'],
                    ['label' => 'Docentes Activos', 'value' => $totalDocentes, 'color' => 'green', 'icon' => '👩‍🏫'],
                    ['label' => 'Grupos Creados', 'value' => $totalGrupos, 'color' => 'amber', 'icon' => '👥'],
                    ['label' => 'Asignaturas', 'value' => $totalAsignaturas, 'color' => 'purple', 'icon' => '📚'],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-2xl p-4 shadow-sm hover:shadow-md transition duration-300 flex flex-col items-center justify-center text-center">
                    <div class="text-4xl mb-2">{{ $card['icon'] }}</div>
                    <span class="text-3xl font-bold text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400">{{ $card['value'] }}</span>
                    <span class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $card['label'] }}</span>
                </div>
            @endforeach
        </div>

        {{-- Módulo informativo + accesos --}}
        <div class="grid md:grid-cols-3 gap-6">

            {{-- ¿Qué puedes hacer aquí? --}}
            <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 p-6 rounded-2xl shadow-md flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <div class="text-blue-600 dark:text-blue-400 text-2xl">🛠️</div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Funciones del Módulo</h2>
                </div>
                <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-300 space-y-1 ml-1">
                    <li>Registrar docentes y estudiantes.</li>
                    <li>Configurar grados, grupos y asignaturas.</li>
                    <li>Gestionar matrículas y periodos académicos.</li>
                    <li>Asignar horarios y docentes.</li>
                    <li>Activar tareas, foros y evaluaciones.</li>
                    <li>Publicar anuncios escolares importantes.</li>
                </ul>
            </div>

            {{-- Gráfico de Estudiantes por Grupo --}}
            <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 p-6 rounded-2xl shadow-md">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Estudiantes por Grupo</h2>
                <canvas id="estudiantesPorGrupoChart" class="w-full h-64"></canvas>
            </div>



            {{-- Accesos rápidos con íconos --}}
            <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 p-6 rounded-2xl shadow-md flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <div class="text-purple-600 dark:text-purple-400 text-2xl">🚀</div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Accesos Rápidos</h2>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    @php
                        $accessos = [
                            ['label' => 'Docentes', 'icon' => '👩‍🏫', 'link' => 'xd'],
                            ['label' => 'Estudiantes', 'icon' => '🎓'],
                            ['label' => 'Grados', 'icon' => '📈'],
                            ['label' => 'Grupos', 'icon' => '👥'],
                            ['label' => 'Asignaturas', 'icon' => '📚'],
                            ['label' => 'Matrículas', 'icon' => '📝'],
                            ['label' => 'Asignamientos', 'icon' => '🧩'],
                            ['label' => 'Foro', 'icon' => '💬'],
                            ['label' => 'Horarios', 'icon' => '🕒'],
                            ['label' => 'Periodo Académico', 'icon' => '📅'],
                        ];
                    @endphp

                    @foreach ($accessos as $item)
                        <div class="flex items-center gap-2 bg-gray-100 dark:bg-neutral-800 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-neutral-700 transition">
                            <span class="text-lg">{{ $item['icon'] }}</span>
                            <span class="text-sm">{{ $item['label'] }}</span>
                            <a href="{{ $item['link'] }}"></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        {{-- Área inferior bienvenida --}}
        <div class="bg-gradient-to-br from-white via-gray-50 to-white dark:from-neutral-900 dark:via-neutral-800 dark:to-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-2xl p-6 text-center shadow-sm">
            <p class="text-gray-600 dark:text-gray-400 text-sm">
                Bienvenido al módulo de gestión escolar del colegio. Usa las tarjetas, accesos y botones para navegar de forma rápida y efectiva.
            </p>
        </div>
    </div>

    @push('js')
<script>
// Declaramos variable global para el gráfico
var estudiantesPorGrupoChart;

function initChart() {
    const ctx = document.getElementById('estudiantesPorGrupoChart')?.getContext('2d');
    if (!ctx) return;

    // Si ya existe el gráfico, lo destruimos para evitar errores
    if (estudiantesPorGrupoChart) {
        estudiantesPorGrupoChart.destroy();
    }

    estudiantesPorGrupoChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($estudiantesPorGrupo)) !!},
            datasets: [{
                label: 'Cantidad de Estudiantes',
                data: {!! json_encode(array_values($estudiantesPorGrupo)) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// Inicializamos el gráfico cuando cargue la página normalmente
document.addEventListener('DOMContentLoaded', initChart);

// Además, cuando Livewire actualice el DOM, re-inicializamos el gráfico
document.addEventListener('livewire:load', initChart);
document.addEventListener('livewire:update', initChart);

</script>
@endpush

</x-layouts.app>
