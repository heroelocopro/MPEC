<?php

namespace App\Livewire\Docente;

use App\Models\Horario;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DocenteHorarios extends Component
{
    // ====== Propiedades ======
    public $horario = [];
    public $colegio;
    public $docente;

    // ====== Métodos principales ======

    /**
     * Carga el horario del docente autenticado.
     */
    public function cargarHorario(): void
    {
        if ($this->docente && isset($this->docente->id)) {
            $this->horario = Horario::where('profesor_id', $this->docente->id)
                ->orderBy('dia')
                ->orderBy('hora_inicio')
                ->get();
        } else {
            $this->horario = collect(); // Evita errores si no hay docente
        }
    }

    // ====== Ciclo de vida Livewire ======

    /**
     * Inicializa los datos del docente y carga su horario.
     */
    public function mount(): void
    {
        $this->docente = Profesor::where('user_id', Auth::id())->first();

        if ($this->docente) {
            $this->colegio = $this->docente->colegio ?? null;
        } else {
            $this->docente = null;
            $this->colegio = null;
        }

        $this->cargarHorario();
    }

    public function render()
    {
        return view('livewire.docente.docente-horarios');
    }
}
