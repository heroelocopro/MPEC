<?php

namespace App\Livewire\Colegio;

use App\Models\asignatura;
use App\Models\asignaturaProfesor;
use App\Models\Colegio;
use App\Models\Grupo;
use App\Models\Horario;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ColegioHorarios extends Component
{
    protected $rules = [
        'colegio_id' => 'required|integer|exists:colegios,id',
        'asignatura_id' => 'required|integer|exists:asignaturas,id',
        'grupo_id' => 'required|integer|exists:grupos,id',
        'profesor_id' => 'required|integer|exists:profesores,id',
        'dia' => 'required|string|in:Lunes,Martes,Miercoles,Jueves,Viernes,Sabado,Domingo',
        'hora_inicio' => 'required|date_format:H:i',
        'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
    ];
    public $modalCreacion = false;
    public $docente_id;
    public $profesor_id;
    public $colegio_id;
    public $asignatura_id;
    public $dia;
    public $hora_inicio;
    public $hora_fin;
    public $grupo_id = null;
    public $horarios = [];
    public $asignaturas = [];
    public function updatedProfesorId($value)
    {
        if($value != null || $value != '')
        {
            $this->asignaturas = asignaturaProfesor::where('profesor_id',$value)->get();
        }
    }
    public function crearHorario()
    {
        $this->validate($this->rules);
        try {
                    // Verificar si ya hay conflicto de horario con ese profesor
            $conflicto = Horario::where('profesor_id', $this->profesor_id)
                ->where('dia', $this->dia)
                ->where(function ($query) {
                    $query->whereBetween('hora_inicio', [$this->hora_inicio, $this->hora_fin])
                        ->orWhereBetween('hora_fin', [$this->hora_inicio, $this->hora_fin])
                        ->orWhere(function ($q) {
                            $q->where('hora_inicio', '<=', $this->hora_inicio)
                                ->where('hora_fin', '>=', $this->hora_fin);
                        });
                })
                ->exists();

            if ($conflicto) {
                $this->addError('hora_inicio', 'Este profesor ya tiene una clase asignada en ese rango de horario.');
                return;
            }
            $datos = [
            'colegio_id' => $this->colegio_id,
            'asignatura_id' => $this->asignatura_id,
            'grupo_id' => $this->grupo_id,
            'profesor_id' => $this->profesor_id,
            'dia' => $this->dia,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin];
            Horario::create($datos);
            $this->limpiar();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function limpiar()
    {
        $this->modalCreacion = false;
        $this->horarios = Horario::where('grupo_id',$this->grupo_id)->get();
    }
    public function updatedGrupoId($valor)
    {
        if($valor != null || $valor != ''){
            $this->horarios = Horario::where('grupo_id',$valor)->get();
        }
    }
    public function render()
    {
        $colegio = Colegio::where('user_id','=',Auth::user()->id)->first();
        $this->colegio_id = $colegio->id;
        $profesores = Profesor::where('colegio_id',$colegio->id)->get();
        $grupos = Grupo::where('colegio_id',$colegio->id)->get();
        return view('livewire.colegio.colegio-horarios', compact('colegio','profesores','grupos'));
    }
}
