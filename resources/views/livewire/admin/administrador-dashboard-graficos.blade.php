<div>

    <div wire:ignore>
      <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
          Estudiantes por Colegio
        </h2>
        <div id="graficoColegios" class="h-[300px] w-full"></div>
      </div>
    </div>


    @push('js')
    <script>
        let chartColegios = null;
        function initGraficoColegios(nombres, cantidades, ids) {
            const chartEl = document.querySelector("#graficoColegios");
            if (!chartEl) return;
            // Verificar datos válidos
            console.log(cantidades);
            if (cantidades.length === 0) {
                console.warn("No hay datos para mostrar en el gráfico.");
                return;
            }

            const isDark = document.documentElement.classList.contains('dark');

            const options = {
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: { show: false },
                    foreColor: isDark ? '#f3f4f6' : '#1f2937',
                    events: {
                        dataPointSelection(event, chartContext, config) {
                            const idx = config.dataPointIndex;
                            const colegioId = ids[idx];
                            if (colegioId) {
                                window.location.href = `/administrador/colegio/${colegioId}`;
                            }
                        }
                    }
                },
                series: [{ name: 'Estudiantes', data: cantidades }],
                xaxis: {
                    categories: nombres,
                    labels: {
                        rotate: -45,
                        style: {
                            colors: isDark ? '#f3f4f6' : '#1f2937'
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        distributed: true,
                        columnWidth: '25%',
                        borderRadius: 4
                    }
                },
                colors: nombres.map((_, i) =>
                    isDark
                        ? `hsl(${(i * 40) % 360}, 70%, 50%)`
                        : `hsl(${(i * 40) % 360}, 60%, 60%)`
                ),
                dataLabels: { enabled: false },
                theme: { mode: isDark ? 'dark' : 'light' }
            };

            // Destruir gráfico anterior si existe
            if (chartColegios) {
                chartColegios.destroy();
            }

            chartColegios = new ApexCharts(chartEl, options);
            chartColegios.render();
        }

        // Escuchar evento de Livewire
        Livewire.on('initGraficoColegios', (datos) => {
            if(datos.length === 3)
        {
            const n = datos[0];
            const c = datos[1];
            const i = datos[2];
            requestAnimationFrame(() => {
                initGraficoColegios(n, c, i);
            });
        }
        });
    </script>
    @endpush

  </div>
