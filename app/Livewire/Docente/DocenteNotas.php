<?php

namespace App\Livewire\Docente;

use App\Models\Actividad;
use App\Models\asignatura;
use App\Models\AsignaturaGrado;
use App\Models\asignaturaProfesor;
use App\Models\Colegio;
use App\Models\configNota;
use App\Models\EstudianteGrupo;
use App\Models\Examen;
use App\Models\Grupo;
use App\Models\nota;
use App\Models\PeriodoAcademico;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;


class DocenteNotas extends Component
{
    public $nota_minima = null;
    public $nota_maxima = null;
    public $colegio;
    public $profesor;
    public $asignaturas;
    public asignatura $asignatura;
    public $grupos;
    public $grupo_id;
    public $grupo;
    public $actividades = [];
    public $examenes = [];
    public $totalNotas;
    public $notas = [
    'actividad' => [],
    'examen' => [],
    ];
    public $color;
    public $periodo;




    public function guardarNotas()
    {
        if(isset($this->notas) && count($this->notas) > 0)
        {
            foreach($this->notas as $nombre => $valores)
            {
                // validamos eel nombre
                    if(isset($valores) && count($valores) > 0)
                    {
                        foreach($valores as $estudianteId => $valor)
                        {
                            foreach($valor as $id => $nota)
                            {
                                        if (!is_numeric($nota) || $nota < $this->nota_minima || $nota > $this->nota_maxima) {
                                    $this->dispatch('alerta', [
                                        'title' => 'Valor inválido',
                                        'text' => 'Ingrese un número entre '.$this->nota_minima.' y '. $this->nota_maxima,
                                        'icon' => 'error',
                                        'toast' => true,
                                        'position' => 'top-end',
                                    ]);
                                    return;
                                }else{
                                   $this->guardarNota($nombre, $estudianteId, $id, $nota);
                                }
                            }
                        }
                    }
            }
        }
    }




     public function updatedNotasActividad($value, $path)
    {
        [$estudianteId, $actividadId] = explode('.', $path);

        $this->guardarNota('actividad', $estudianteId, $actividadId, $value);
    }

    public function updatedNotasExamen($value, $path)
    {
        [$estudianteId, $examenId] = explode('.', $path);
        $this->guardarNota('examen', $estudianteId, $examenId, $value);
    }





protected function guardarNota($tipo, $estudianteId, $notableId, $valor)
{
    // Validar tipo permitido
    $notableClass = match ($tipo) {
        'actividad' => \App\Models\Actividad::class,
        'examen' => \App\Models\Examen::class,
        default => null,
    };



    if (!$notableClass) {
        $this->dispatch('alerta', [
            'title' => 'Tipo de nota inválido',
            'text' => 'Tipo no reconocido: ' . $tipo,
            'icon' => 'error',
            'toast' => true,
            'position' => 'top-end',
        ]);
        return;
    }
    // obtenemos periodo actual
    $periodo = PeriodoAcademico::periodoActual($this->colegio->id);
    try {
        Nota::updateOrCreate(
            [
                'grupo_id' => $this->grupo_id,
                'estudiante_id' => $estudianteId,
                'asignatura_id' => $this->asignatura->id,
                'notable_id' => $notableId,
                'notable_type' => $notableClass,
                'ano' => now()->format('Y'),
                'periodo_id' => $periodo->id,

            ],
            [
                'valor' => $valor,
            ]
        );

        $this->dispatch('alerta', [
            'title' => 'Cambio de notas exitoso',
            'text' => '¡Se guardó correctamente!',
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
        ]);
    } catch (\Throwable $th) {
        $this->dispatch('alerta', [
            'title' => 'Error al guardar',
            'text' => $th->getMessage(),
            'icon' => 'error',
            'toast' => true,
            'position' => 'top-end',
        ]);
    }
}



    public function cargarNotas()
    {
        // Obtener todas las notas del grupo
        $notasDB = Nota::where('asignatura_id', $this->asignatura->id)->where('grupo_id',$this->grupo_id)->get();
        $this->notas = [
            'actividad' => [],
            'examen' => [],
            ];

        foreach ($notasDB as $nota) {
            $tipo = class_basename($nota->notable_type); // 'Actividad' o 'Examen'
            $tipo = strtolower($tipo); // 'actividad' o 'examen'

            // Establecer en el array el valor de la nota

            $this->notas[$tipo][$nota->estudiante_id][$nota->notable_id] = $nota->valor;
        }
    }

    public function updatedAsignaturaId($value)
    {
        if($value != null || $value != '')
        {
            $this->grupo_id = null;
        }
    }


    public function updatedGrupoId($value)
    {
        if($value != null || $value != '')
        {
            $this->grupo = EstudianteGrupo::where('grupo_id',$value)->get();
            $this->actividades = Actividad::where('grupo_id',$value)->where('asignatura_id',$this->asignatura->id)->get();
            $this->examenes = Examen::where('grupo_id',$value)->where('asignatura_id',$this->asignatura->id)->get();
            $this->totalNotas = count($this->examenes) + count($this->actividades);
            $this->cargarNotas();
        }
    }
    // seleccionar los grupos de los grados que tengan esa asignatura en su lista.
    public function cambiarAsignatura($id)
    {
        $this->asignatura = asignatura::findOrFail($id);
        $this->grupos = collect(DB::select("
            SELECT grupos.*
            FROM asignatura_grados
            INNER JOIN grupos ON asignatura_grados.grado_id = grupos.grado_id
            WHERE asignatura_grados.asignatura_id = ?
        ", [$this->asignatura->id]));
        $this->grupo_id = '';
        $this->actividades = [];
        $this->examenes = [];
        $this->grupo = [];
    }
    public function mount()
    {
        $this->profesor = Profesor::where('user_id',Auth::user()->id)->first();
        $this->colegio = $this->profesor->colegio;
        $this->asignaturas = asignaturaProfesor::where('profesor_id',$this->profesor->id)->get();
        $this->periodo = PeriodoAcademico::periodoActual($this->colegio->id);
        $configNota = configNota::where('colegio_id',$this->colegio->id)->first();
        $this->nota_minima = $configNota->nota_minima;
        $this->nota_maxima = $configNota->nota_maxima;



    }
    public function render()
    {
        return view('livewire.docente.docente-notas');
    }
}
