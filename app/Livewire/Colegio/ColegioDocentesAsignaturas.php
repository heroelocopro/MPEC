<?php

namespace App\Livewire\Colegio;

use App\Models\asignatura;
use App\Models\asignaturaProfesor;
use App\Models\Colegio;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ColegioDocentesAsignaturas extends Component
{
    // protecteds
    protected $rules = [
        'asignatura_id' => 'required|integer|exists:asignaturas,id',
        'profesor_id' => 'required|integer|exists:profesores,id',
    ];
    protected $listeners = [];
    // variables para mi desarrollo
    public $asignatura_id;
    public $profesor_id;
    public $profesor;
    public $asignaturasProfesor = [];
    // variables modales
    public $modalCreacion = false;
    // metodos mios
    public function eliminarAsignaturaProfesor($id)
    {
        try {
            asignaturaProfesor::findOrFail($id)->delete();
             $data = [
            'title' => 'Asignatura-Docente',
            'text' => 'Asignacion Eliminada!',
            'icon' => 'success'
            ];
            $this->limpiarAsignacion();
        } catch (\Throwable $th) {
            $data = [
            'title' => 'Error al asignar la asignatura!',
            'text' => $th->getMessage(),
            'icon' => 'error'
            ];
        }
        $this->dispatch('alerta',$data);
    }
    public function asignarAsignaturaProfesor()
    {
        $this->validate($this->rules);
        try {
            $datos = [
                'asignatura_id' => $this->asignatura_id,
                'profesor_id' => $this->profesor_id,
            ];
            asignaturaProfesor::create($datos);

             $data = [
            'title' => 'Asignatura-Docente',
            'text' => 'Asignacion exitosa!',
            'icon' => 'success'
            ];
            $this->limpiarAsignacion();
        } catch (\Throwable $th) {
            $data = [
            'title' => 'Error al crear estudiante!',
            'text' => $th->getMessage(),
            'icon' => 'error'
            ];
        }
        $this->dispatch('alerta',$data);
    }

    public function limpiarAsignacion()
    {
       $this->modalCreacion = false;
       $this->asignaturasProfesor = asignaturaProfesor::where('profesor_id',$this->profesor_id)->get();
    }

    // metodos de livewire render booted mount
    public function updatedProfesorId($valor)
    {
        if($valor == '' || $valor == null)
        {
            $this->profesor_id = '';
            $this->profesor = '';
            $this->asignaturasProfesor = [];
        }else{
            $this->profesor = Profesor::findOrFail($this->profesor_id);
            $this->asignaturasProfesor = asignaturaProfesor::where('profesor_id',$this->profesor_id)->get();
        }
    }
    public function mount()
    {
        $this->modalCreacion = false;
        $this->asignaturasProfesor = [];
        $this->profesor_id = '';
        $this->profesor = '';
    }
    public function render()
    {
        $profesorId = $this->profesor_id;
        $colegio = Colegio::where('user_id',Auth::user()->id)->first();
        $profesores = Profesor::where('colegio_id',$colegio->id)->get();
        if($profesorId == null || $profesorId == '')
        {
            $asignaturas = asignatura::where('colegio_id',$colegio->id)->get();
        }else{
            $asignaturas = Asignatura::where('colegio_id', $colegio->id)
            ->whereDoesntHave('profesores', function ($query) use ($profesorId) {
                $query->where('profesor_id', $profesorId);
            })
            ->get();
        }

        return view('livewire.colegio.colegio-docentes-asignaturas', compact('colegio','profesores','asignaturas'));
    }
}
