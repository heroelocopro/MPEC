<?php

namespace App\Livewire\Colegio;

use App\Models\Colegio;
use App\Models\Estudiante;
use App\Models\EstudianteGrupo;
use App\Models\Grupo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ColegioEstudiantesGrupos extends Component
{
    public $colegio;
    public $colegio_id;
    public $grupo_id;
    public $estudiante_id;
    public $estudiantes = [];
    public $estudiantesGrupos = [];
    public $modalCreacion = false;
    public $grupoInfo = null;

    protected $rules = [
        'estudiante_id' => 'required|integer|exists:estudiantes,id',
        'grupo_id' => 'required|integer|exists:grupos,id',
        'colegio_id' => 'required|integer|exists:colegios,id',
    ];

    protected $listeners = [
        'eliminarEstudianteGrupo' => 'eliminarEstudianteGrupo',
    ];

    public function mount()
    {
        $colegio = Colegio::where('user_id', Auth::user()->id)->first();
        if ($colegio) {
            $this->colegio = $colegio;
            $this->colegio_id = $colegio->id;
        } else {
            abort(403, 'No tiene colegio asignado.');
        }
    }

    public function updatedGrupoId($value)
    {
        if (!$value) {
            $this->grupoInfo = null;
            $this->estudiantes = [];
            $this->estudiantesGrupos = [];
            return;
        }

        $this->grupoInfo = Grupo::find($value);

        if (!$this->grupoInfo) {
            $this->estudiantes = [];
            $this->estudiantesGrupos = [];
            return;
        }

        // Estudiantes disponibles:
        // - Que pertenezcan al colegio actual
        // - Que estén matriculados activamente en el grado del grupo seleccionado
        // - Que aún NO estén asignados a ningún grupo en este colegio
        $this->estudiantes = Estudiante::where('colegio_id', $this->colegio_id)
            ->whereHas('matriculas', function ($query) {
                $query->where('estado', 'activo')
                    ->where('grado_id', $this->grupoInfo->grado_id);
            })
            ->whereDoesntHave('estudiantesGrupos', function ($query) {
                $query->where('colegio_id', $this->colegio_id);
            })
            ->get();

        // Estudiantes ya asignados a este grupo
        $this->estudiantesGrupos = EstudianteGrupo::where('colegio_id', $this->colegio_id)
            ->where('grupo_id', $value)
            ->with('estudiante')
            ->get();
    }



    public function asignarEstudianteGrupo()
    {
        $this->validate();

        try {
            $exists = EstudianteGrupo::where('estudiante_id', $this->estudiante_id)
                ->where('grupo_id', $this->grupo_id)
                ->where('colegio_id', $this->colegio_id)
                ->exists();

            if ($exists) {
                $this->dispatch('alerta', [
                    'title' => 'Asignación duplicada',
                    'text' => 'Este estudiante ya está asignado a este grupo.',
                    'icon' => 'warning',
                ]);
                return;
            }
            $yaAsignado = EstudianteGrupo::where('estudiante_id', $this->estudiante_id)
                ->where('colegio_id', $this->colegio_id)
                ->exists();

            if ($yaAsignado) {
                $this->dispatch('alerta', [
                    'title' => 'Estudiante ya asignado',
                    'text' => 'Este estudiante ya pertenece a un grupo en este colegio.',
                    'icon' => 'warning',
                ]);
                return;
            }


            EstudianteGrupo::create([
                'estudiante_id' => $this->estudiante_id,
                'grupo_id' => $this->grupo_id,
                'colegio_id' => $this->colegio_id,
            ]);

            $this->dispatch('alerta', [
                'title' => 'Asignación exitosa',
                'text' => 'Estudiante asignado con éxito.',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);

            $this->limpiarAsignacion();
            $this->updatedGrupoId($this->grupo_id); // Actualizar listas

        } catch (\Throwable $e) {
            $this->dispatch('alerta', [
                'title' => 'Error al asignar estudiante',
                'text' => $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function eliminarEstudianteGrupo($id)
    {
        try {
            $estudianteGrupo = EstudianteGrupo::findOrFail($id);
            $estudianteGrupo->delete();

            $this->dispatch('alerta', [
                'title' => 'Desvinculación exitosa',
                'text' => 'Estudiante desvinculado con éxito.',
                'icon' => 'success',
            ]);

            $this->updatedGrupoId($this->grupo_id); // Actualizar listas

        } catch (\Throwable $e) {
            $this->dispatch('alerta', [
                'title' => 'Error al desvincular',
                'text' => $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function limpiarAsignacion()
    {
        $this->estudiante_id = null;
        $this->modalCreacion = false;
        // No se limpia grupo_id ni grupoInfo para mantener el grupo activo
    }

    public function render()
    {
        $grupos = Grupo::where('colegio_id', $this->colegio_id)->get();

        return view('livewire.colegio.colegio-estudiantes-grupos', compact('grupos'));
    }
}
