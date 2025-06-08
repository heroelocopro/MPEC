<?php

namespace App\Livewire\Docente;

use App\Models\asignatura;
use App\Models\AsignaturaGrado;
use App\Models\asignaturaProfesor;
use App\Models\Asistencia;
use App\Models\Colegio;
use App\Models\EstudianteGrupo;
use App\Models\Grupo;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DocenteAsistencias extends Component
{
    public $colegio;
    public $profesor;
    public $grados = [];
    public $grupos;
    public $grupo;
    public $grupo_id;
    public $estudiantes = [];
    public $asistencias = [];
    public $justificaciones = [];
    public $asignatura;
    public $asignaturas = [];

    // variables de guardado
    public $estudiante_id;
    public $colegio_id;
    public $asignatura_id;
    public $profesor_id;
    public $estado;
    public $justificacion;

    public function guardarAsistencias()
    {

        foreach($this->asistencias as $id => $asistencia)
        {
            $this->estudiante_id = $id;
            $this->estado = $asistencia['estado'];
            $this->justificacion = $asistencia['justificacion'] ?? null;
            $this->asignatura_id = $this->asignatura->id;
            $this->profesor_id = $this->profesor->id;
            $this->colegio_id = $this->colegio->id;
            $this->grupo_id = $this->grupo->id;
            // reglas
            $rules = [
                'estudiante_id'   => 'required|exists:estudiantes,id',
                'grupo_id'        => 'required|exists:grupos,id',
                'colegio_id'      => 'required|exists:colegios,id',
                'asignatura_id'   => 'required|exists:asignaturas,id',
                'profesor_id'     => 'required|exists:profesores,id',
                'estado'          => 'required|in:presente,ausente,tarde,justificado',
                'justificacion'   => 'nullable|string|max:255',
            ];
            $this->validate($rules);
            $datos = [
                'estudiante_id' => $this->estudiante_id,
                'grupo_id' => $this->grupo_id,
                'colegio_id' =>  $this->colegio_id,
                'asignatura_id' => $this->asignatura_id,
                'profesor_id' => $this->profesor_id,
                'fecha'  => now()->toDateString(),        // '2025-05-06'
                'bloque' => now()->format('H:i'),
                'estado' => $this->estado,
                'justificacion' => $this->justificacion,
            ];

            try {
                Asistencia::updateOrCreate($datos);
                $this->dispatch('alerta', [
                    'title' => 'Asistencias Registradas',
                    'text' => '¡Se guardó correctamente!',
                    'icon' => 'success',
                    'toast' => true,
                    'position' => 'top-end',
                ]);

            } catch (\Throwable $th) {
                $this->dispatch('alerta', [
                                        'title' => 'Error',
                                        'text' => $th->getMessage(),
                                        'icon' => 'error',
                                        'toast' => true,
                                        'position' => 'top-end',
                                    ]);
            }

        }
    }

    public function cargarAsistenciasDelDia()
    {
        // $hoy = \Carbon\Carbon::today();

        $asistencias = \App\Models\Asistencia::whereDate('fecha', now()->today())
            ->where('colegio_id', $this->colegio->id)
            ->where('profesor_id', $this->profesor->id)
            ->where('grupo_id', $this->grupo->id)
            ->where('asignatura_id', $this->asignatura->id)
            ->get();
        // Convertir al formato que espera wire:model
        $this->asistencias = [];

        foreach ($asistencias as $a) {
            $this->asistencias[$a->estudiante_id] = [
                'estado' => $a->estado,
                'justificacion' => $a->justificacion,
            ];
        }
    }


    public function updatedAsistencias($value,$path)
    {

    }
    public function updatedAsignaturaId($value)
    {
        if($value != '' || $value != null)
        {
            $this->asignatura = asignatura::find($value);
            $this->estudiantes = [];
            $this->grupo = null;
            $this->asistencias = [];
            $this->grupos = [];
            $this->grupo_id = null;

            $asignaturaGrado = AsignaturaGrado::where('asignatura_id',$value)->get();
            foreach($asignaturaGrado as $ag )
            {
                array_push($this->grados,$ag->grado);
            }
            foreach($this->grados as $g)
            {
                 $this->grupos = $g->grupos;
            }
        }else{
            $this->grados = [];
            $this->grupos = [];
            $this->grupo_id = null;
            $this->estudiantes = [];
        }
    }

    public function updatedGrupoId($value)
    {
        if($value != '' || $value != null)
        {
            $this->estudiantes = [];
            $this->grupo = null;
            $this->grupo = EstudianteGrupo::where('grupo_id',$value)->get();


            foreach($this->grupo as $g)
            {
                array_push($this->estudiantes,$g->estudiante);
            }
            $this->grupo = grupo::findOrFail($value);
            $this->cargarAsistenciasDelDia();
        }else{
            $this->estudiantes = [];
            $this->grupo = null;
        }
    }
    public function mount()
    {
        $this->profesor = Profesor::where('user_id',Auth::user()->id)->first();
        $this->colegio = $this->profesor->colegio;
        $this->estudiantes = [];
        $asignaturas = asignaturaProfesor::where('profesor_id',$this->profesor->id)->get();
            foreach($asignaturas as $asignatura  )
            {
                array_push($this->asignaturas,$asignatura->asignatura);
            }
    }
    // idea principal
    // seleccionar una asignatura -> grupo y tener aisstencias
    public function render()
    {
        return view('livewire.docente.docente-asistencias');
    }
}
