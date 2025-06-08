<?php

namespace App\Livewire\Colegio;

use App\Models\asignatura;
use App\Models\AsignaturaGrado;
use App\Models\Colegio;
use App\Models\Grado;
use App\Models\Grupo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ColegioAsignaturasGrados extends Component
{
    protected $listeners = ['eliminarAsignacionAsignaturaGrado' => 'eliminarAsignacionAsignaturaGrado'];
    protected $rules = ['grado_id' => 'required|integer|exists:grados,id','asignatura_id' => 'required|integer|exists:asignaturas,id',];
    public $grupo_id;
    public $grado_id;
    public $asignatura_id;
    public $grado = null;
    public $asignaturasGrados = [];
    public $modalCreacion = false;

    public function eliminarAsignacionAsignaturaGrado($id)
    {
        try {
            AsignaturaGrado::findOrFail($id)->delete();
            $data = [
            'title' => 'Asignatura-Grado',
            'text' => 'Desvinculacion Exitosa!',
            'icon' => 'success'
            ];
            $this->limpiarAsignacion();
        } catch (\Throwable $th) {
            $data = [
            'title' => 'Asignatura-Grado',
            'text' => 'Desvinculacion Fallida! ' . $th->getMessage(),
            'icon' => 'error'
            ];
        }
        $this->dispatch('alerta',$data);
    }

    public function asignarAsignaturaGrado()
    {
        $this->validate($this->rules);
        try {
            $datos = [
                'asignatura_id' => $this->asignatura_id,
                'grado_id' => $this->grado_id,
            ];
            AsignaturaGrado::create($datos);
            $data = [
            'title' => 'Asignatura-Grado',
            'text' => 'Asignacion Exitosa!',
            'icon' => 'success'
            ];
            $this->limpiarAsignacion();
        } catch (\Throwable $th) {
            $data = [
            'title' => 'Asignatura-Grado',
            'text' => 'Asignacion Fallida! ' . $th->getMessage(),
            'icon' => 'error'
            ];
        }
        $this->dispatch('alerta',$data);
    }

    public function limpiarAsignacion()
    {
        $this->modalCreacion = false;
        $this->asignatura_id = '';
        $this->asignaturasGrados = AsignaturaGrado::where('grado_id',$this->grado_id)->get();
    }

    public function updatedGradoId($value)
    {
        if($value != null || $value != '')
        {
            $this->asignaturasGrados = AsignaturaGrado::where('grado_id',$value)->get();
            $this->grado = Grado::findOrFail($value);
        }
    }
    public function render()
    {
        $colegio = Colegio::where('user_id',Auth::user()->id)->first();
        $grupos = Grupo::where('colegio_id',$colegio->id)->get();
        $grados = Grado::where('colegio_id',$colegio->id)->get();
        // $grado = Grado::find($grado_id); // o el que estés usando
        if($this->grado == null)
        {
            $asignaturas = [];
        }else{
            $asignaturas = Asignatura::whereNotIn('id', $this->grado->asignaturas->pluck('id'))->get();
        }
        return view('livewire.colegio.colegio-asignaturas-grados',compact('colegio','grupos','grados','asignaturas'));
    }
}
