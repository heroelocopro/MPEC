<div>
    <div class="flex flex-col gap-6">

        {{-- Header del panel --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Panel de Gestión Escolar</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Hola, administra fácilmente tu colegio desde aquí.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('colegio-horarios') }}">
                    <x-button>Editar Horarios</x-button>
                </a>
                <a href="{{ route('colegio-anuncios') }}">
                    <x-button color="secondary">Crear Anuncio</x-button>
                </a>
            </div>
        </div>

        {{-- Tarjetas resumen --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $resumen = [
                    ['title' => 'Alumnos Registrados', 'count' => $totalEstudiantes, 'color' => 'indigo', 'icon' => '🎓'],
                    ['title' => 'Profesores Activos', 'count' => $totalDocentes, 'color' => 'emerald', 'icon' => '🧑‍🏫'],
                    ['title' => 'Clases Programadas', 'count' => $totalClasesProgramadas, 'color' => 'yellow', 'icon' => '📅'],
                    ['title' => 'Materias Disponibles', 'count' => $totalAsignaturas, 'color' => 'fuchsia', 'icon' => '📖'],
                ];
            @endphp

            @foreach ($resumen as $item)
                <div
                    class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-2xl p-5 shadow hover:shadow-lg transition flex flex-col items-center justify-center text-center">
                    <div class="text-4xl mb-2">{{ $item['icon'] }}</div>
                    <span
                        class="text-3xl font-extrabold text-{{ $item['color'] }}-600 dark:text-{{ $item['color'] }}-400">{{ $item['count'] }}</span>
                    <span class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $item['title'] }}</span>
                </div>
            @endforeach
        </div>

        {{-- Sección principal con info, gráfico y accesos --}}
        <div class="grid md:grid-cols-3 gap-6 mt-4">

            {{-- Descripción de funciones --}}
            <section
                class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-2xl p-6 shadow flex flex-col gap-3">
                <header class="flex items-center gap-3">
                    <span class="text-indigo-600 dark:text-indigo-400 text-2xl">⚙️</span>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">¿Qué puedes hacer?</h2>
                </header>
                <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-300 space-y-1">
                    <li>Agregar y gestionar docentes y alumnos.</li>
                    <li>Configurar grados, grupos y materias.</li>
                    <li>Administrar matrículas y períodos.</li>
                    <li>Asignar horarios y profesores.</li>
                    <li>Activar evaluaciones, tareas y foros.</li>
                    <li>Publicar noticias y comunicados.</li>
                </ul>
            </section>

            {{-- Gráfico: Alumnos por clase --}}
            {{-- Gráfico de Estudiantes por Grupo --}}
        <div wire:ignore class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 p-6 rounded-2xl shadow-md">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Estudiantes por Grupo</h2>
            <canvas id="estudiantesPorGrupoChart" wire:ignore class="w-full h-64"></canvas>
        </div>

            {{-- Accesos rápidos --}}
            <section
                class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-2xl p-6 shadow flex flex-col gap-3">
                <header class="flex items-center gap-3">
                    <span class="text-fuchsia-600 dark:text-fuchsia-400 text-2xl">🚀</span>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Atajos Rápidos</h2>
                </header>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    @php
                        $atajos = [
                            ['label' => 'Profesores', 'icon' => '🧑‍🏫'],
                            ['label' => 'Alumnos', 'icon' => '🎒'],
                            ['label' => 'Grados', 'icon' => '📊'],
                            ['label' => 'Grupos', 'icon' => '👥'],
                            ['label' => 'Materias', 'icon' => '📚'],
                            ['label' => 'Matrículas', 'icon' => '📝'],
                            ['label' => 'Asignaciones', 'icon' => '🧩'],
                            ['label' => 'Foros', 'icon' => '💬'],
                            ['label' => 'Horarios', 'icon' => '⏰'],
                            ['label' => 'Períodos', 'icon' => '📆'],
                        ];
                    @endphp

                    @foreach ($atajos as $acceso)
                        <div
                            class="flex items-center gap-2 bg-gray-100 dark:bg-neutral-800 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-neutral-700 transition cursor-pointer select-none">
                            <span class="text-lg">{{ $acceso['icon'] }}</span>
                            <span>{{ $acceso['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        {{-- Mensaje de bienvenida final --}}
        <div
            class="bg-gradient-to-br from-white via-gray-50 to-white dark:from-neutral-900 dark:via-neutral-800 dark:to-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-2xl p-6 mt-6 text-center shadow-sm">
            <p class="text-gray-600 dark:text-gray-400 text-sm">
                Bienvenido al panel administrativo. Utiliza los accesos y herramientas para gestionar tu colegio de forma ágil y segura.
            </p>
        </div>
    </div>

    @push('js')
    <script>
        function initChart() {
            const ctx = document.getElementById('estudiantesPorGrupoChart');
            if (!ctx) return;

            // Destruir el gráfico anterior si existe
            if (ctx.chart) {
                ctx.chart.destroy();
            }

            const data = {
                labels: @json(array_keys($estudiantesPorGrupo)),
                datasets: [{
                    label: 'Cantidad de Estudiantes',
                    data: @json(array_values($estudiantesPorGrupo)),
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            };

            // Guardar referencia del gráfico en el elemento canvas
            ctx.chart = new Chart(ctx, config);
        }

        // Inicializar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', initChart);

        // Livewire hooks para manejar la navegación
        document.addEventListener('livewire:init', () => {
            Livewire.on('chartUpdate', () => {
                initChart();
            });
        });

        // Volver a cargar cuando Livewire actualice el DOM
        document.addEventListener('livewire:update', initChart);
    </script>
    @endpush
</div>
