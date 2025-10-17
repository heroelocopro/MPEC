<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotasFinalesService;

class GenerarNotasFinales extends Command
{
    /**
     * Nombre del comando (ejecución en consola).
     */
    protected $signature = 'app:generar-notas-finales';

    /**
     * Descripción del comando.
     */
    protected $description = 'Genera notas finales para todos los grupos de colegios en los periodos que terminan mañana.';

    /**
     * Inyección del service.
     */
    protected NotasFinalesService $notasFinalesService;

    public function __construct(NotasFinalesService $notasFinalesService)
    {
        parent::__construct();
        $this->notasFinalesService = $notasFinalesService;
    }

    /**
     * Ejecución principal del comando.
     */
    public function handle()
    {
        $manana = now()->addDay();
        $resultado = $this->notasFinalesService->generarParaPeriodosQueFinalizan($manana);
        $this->info($resultado);
    }

    /**
     * Comando opcional para cerrar notas de un periodo específico.
     */
    public function cerrarNotas($periodoId)
    {
        $resultado = $this->notasFinalesService->cerrarNotas($periodoId);
        $this->info($resultado);
    }
}
