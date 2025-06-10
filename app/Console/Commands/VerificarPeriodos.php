<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerificarPeriodos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verificar-periodos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hoy = now();

        // Inactivar los periodos vencidos
        \App\Models\PeriodoAcademico::where('estado', 'activo')
            ->where('fecha_fin', '<', $hoy)
            ->update(['estado' => 'inactivo']);

        // Activar el periodo actual si está en rango
        \App\Models\PeriodoAcademico::where('estado', 'inactivo')
            ->where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->update(['estado' => 'activo']);

        $this->info('Periodos actualizados según la fecha actual.');
    }

}
