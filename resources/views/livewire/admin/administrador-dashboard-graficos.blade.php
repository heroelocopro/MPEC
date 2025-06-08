<div>
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Estudiantes por Colegio</h2>
        <div class="relative h-[250px] w-full max-w-[500px] mx-auto">
            <canvas id="graficoColegios"></canvas>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('graficoColegios').getContext('2d');
                const isDarkMode = document.documentElement.classList.contains('dark');

                const backgroundColor = isDarkMode ? 'rgba(255, 255, 255, 0.7)' : 'rgba(59, 130, 246, 0.5)';
                const borderColor = isDarkMode ? 'rgba(255, 255, 255, 1)' : 'rgba(59, 130, 246, 1)';
                const textColor = isDarkMode ? '#E5E7EB' : '#1F2937';

                const data = {
                    labels: @json(array_column($colegiosData, 'nombre')),
                    datasets: [{
                        label: 'Cantidad de Estudiantes',
                        data: @json(array_column($colegiosData, 'estudiantes')),
                        backgroundColor: backgroundColor,
                        borderColor: borderColor,
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                };

                new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: textColor
                                },
                                grid: {
                                    color: isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)'
                                }
                            },
                            x: {
                                ticks: {
                                    color: textColor
                                },
                                grid: {
                                    color: isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)'
                                }
                            }
                        }
                    }
                });
            });
        </script>
    </div>

</div>
