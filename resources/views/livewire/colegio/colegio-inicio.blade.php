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
                <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-2xl p-5 shadow hover:shadow-lg transition flex flex-col items-center justify-center text-center">
                    <div class="text-4xl mb-2">{{ $item['icon'] }}</div>
                    <span class="text-3xl font-extrabold text-{{ $item['color'] }}-600 dark:text-{{ $item['color'] }}-400">{{ $item['count'] }}</span>
                    <span class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $item['title'] }}</span>
                </div>
            @endforeach
        </div>

        {{-- Sección principal con info, gráfico y accesos --}}
        <div class="grid md:grid-cols-3 gap-6 mt-4">
            {{-- Descripción de funciones --}}
            <section class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-2xl p-6 shadow flex flex-col gap-3">
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

            {{-- Gráfico de Estudiantes por Grupo --}}
            <div wire:ignore class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 p-6 rounded-2xl shadow-md col-span-1">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Estudiantes por Grupo</h2>
                <div id="graficoEstudiantes" class="w-full h-[300px]"></div>
            </div>

            {{-- Accesos rápidos --}}
            <section class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-2xl p-6 shadow flex flex-col gap-3">
                <header class="flex items-center gap-3">
                    <span class="text-fuchsia-600 dark:text-fuchsia-400 text-2xl">🚀</span>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Atajos Rápidos</h2>
                </header>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    @php
                        $atajos = [
                            ['label' => 'Profesores', 'icon' => '🧑‍🏫','link' => route("colegio-docentes")],
                            ['label' => 'Alumnos', 'icon' => '🎒','link' => route("colegio-estudiantes")],
                            ['label' => 'Grados', 'icon' => '📊','link' => route("colegio-grados")],
                            ['label' => 'Grupos', 'icon' => '👥','link' => route("colegio-grupos")],
                            ['label' => 'Materias', 'icon' => '📚','link' => route("colegio-asignaturas")],
                            ['label' => 'Matrículas', 'icon' => '📝','link' => route("colegio-matriculas")],
                            // ['label' => 'Asignaciones', 'icon' => '🧩','link' => route("colegio-")],
                            ['label' => 'Foros', 'icon' => '💬','link' => route("foro")],
                            ['label' => 'Horarios', 'icon' => '⏰','link' => route("colegio-horarios")],
                            ['label' => 'Períodos', 'icon' => '📆','link' => route("colegio-periodos")],
                            ['label' => 'Historial Academico', 'icon' => '🗂️','link' => route("colegio-historial-academico")],
                        ];
                    @endphp

                    @foreach ($atajos as $acceso)
                        <div class="flex items-center gap-2 bg-gray-100 dark:bg-neutral-800 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-neutral-700 transition cursor-pointer select-none">
                            <a href="{{ $acceso['link'] }}">
                            <span class="text-lg">{{ $acceso['icon'] }}</span>
                            <span>{{ $acceso['label'] }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        {{-- Mensaje de bienvenida final --}}
        <div class="bg-gradient-to-br from-white via-gray-50 to-white dark:from-neutral-900 dark:via-neutral-800 dark:to-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-2xl p-6 mt-6 text-center shadow-sm">
            <p class="text-gray-600 dark:text-gray-400 text-sm">
                Bienvenido al panel administrativo. Utiliza los accesos y herramientas para gestionar tu colegio de forma ágil y segura.
            </p>
        </div>
    </div>
</div>

@push('js')
{{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
<style>
    /* Cambia el cursor a pointer al pasar el mouse sobre las barras */
    #graficoEstudiantes .apexcharts-series path {
        cursor: pointer;
    }
</style>
<script>
    Livewire.on('chartUpdate', (datos) => {
        console.log('Datos recibidos para gráfico:', datos);
        const nombres = datos[0]; // Nombres de los grupos
        const cantidades = datos[1]; // Cantidades por grupo
        const ids = datos[2];

        const chartContainer = document.querySelector("#graficoEstudiantes");

        // Elimina gráfico previo si existe
        if (chartContainer && chartContainer.innerHTML !== '') {
            chartContainer.innerHTML = '';
        }

        const options = {
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: false },
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        const index = config.dataPointIndex;
                        const idGrupo = ids[index];

                        // Redirige al grupo correspondiente (ajusta la ruta si usas ID en lugar de nombre)
                        window.location.href = `/colegio/grupo/${encodeURIComponent(idGrupo)}`;
                    }
                }
            },
            series: [{
                name: 'Estudiantes',
                data: cantidades
            }],
            xaxis: {
                categories: nombres,
                title: { text: 'Grupos' },
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                title: { text: 'Cantidad de Estudiantes' },
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 4,
                    columnWidth: '50%',
                }
            },
            colors: ['#1E88E5'],
            tooltip: {
                y: {
                    formatter: function (val) {
                        return `${val} estudiantes`;
                    }
                }
            },
            noData: {
                text: 'No hay datos...',
                align: 'center',
                verticalAlign: 'middle',
                style: {
                    color: '#666',
                    fontSize: '14px'
                }
            }
        };

        const chart = new ApexCharts(chartContainer, options);
        chart.render();
    });
</script>



@endpush
