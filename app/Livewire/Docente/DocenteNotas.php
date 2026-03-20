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









protected function guardarNota($tipo, $estudianteId, $notableId, $valor)
{
    $estudianteId = (int) str_replace('est_','',$estudianteId);
    
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
        if($tipo === 'actividad')
        {
            $notableId = (int) str_replace('act_','',$notableId);
        } else{
            $notableId = (int) str_replace('eva_','',$notableId);
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
    // Obtener el periodo activo actual del colegio
    $periodo = PeriodoAcademico::periodoActual($this->colegio->id);
    // Si no hay periodo activo, no cargar nada
    if (!$periodo) {
        $this->notas = [
            'actividad' => [],
            'examen' => [],
        ];
        $this->dispatch('alerta', [
            'title' => 'Error',
            'text' => 'no hay periodo activo',
            'icon' => 'error',
            'toast' => true,
            'position' => 'top-end',
        ]);
        return;
         // salir sin hacer consultas
    }


    // Obtener todas las notas del grupo en el periodo actual
    $notasDB = Nota::where('asignatura_id', $this->asignatura->id)
        ->where('grupo_id', $this->grupo_id)
        ->where('periodo_id', $periodo->id)
        ->get();
    $this->notas = [
        'actividad' => [],
        'examen' => [],
    ];

    foreach ($notasDB as $nota) {
        $tipo = strtolower(class_basename($nota->notable_type)); // 'actividad' o 'examen'
        $estId = 'est_'.$nota->estudiante_id;
        if($tipo === 'actividad')
            {
                $tipoId =  'act_'.$nota->notable_id;
                
            }else {
                $tipoId = 'eva_'.$nota->notable_id;
            }
        
        $this->notas[$tipo][$estId][$tipoId] = $nota->valor;
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
            $this->actividades = Actividad::where('grupo_id',$value)
                                            ->where('asignatura_id',$this->asignatura->id)
                                            ->where('periodo_id',PeriodoAcademico::periodoActual($this->colegio->id)->id ?? 0)
                                            ->get();
            $this->examenes = Examen::where('grupo_id',$value)
                                    ->where('asignatura_id',$this->asignatura->id)
                                    ->where('periodo_id',PeriodoAcademico::periodoActual($this->colegio->id)->id ?? 0)
                                    ->get();
            $this->totalNotas = count($this->examenes) + count($this->actividades);
            $this->cargarNotas();
        }
    }
    // seleccionar los grupos de los grados que tengan esa asignatura en su lista.
    public function cambiarAsignatura($id)
    {
        $this->asignatura = asignatura::findOrFail($id);
        $this->grupos = collect(DB::select("
            SELECT grupos.*, grados.nombre as gradoNombre
            FROM asignatura_grados
            INNER JOIN grupos ON asignatura_grados.grado_id = grupos.grado_id
            INNER JOIN grados on grupos.grado_id = grados.id
            WHERE asignatura_grados.asignatura_id = ?
        ", [$this->asignatura->id]));
        $this->grupo_id = '';
        $this->actividades = [];
        $this->examenes = [];
        $this->grupo = [];
    }
    public function mount()
    {
        $this->profesor = Profesor::where('user_id', Auth::id())->first() ?? (object)[];
        $this->colegio = isset($this->profesor->colegio) ? $this->profesor->colegio : (object)['id' => null];
        $this->asignaturas = isset($this->profesor->id)
            ? asignaturaProfesor::where('profesor_id', $this->profesor->id)->get()
            : collect(); // colección vacía

        $this->periodo = isset($this->colegio->id)
            ? PeriodoAcademico::periodoActual($this->colegio->id)
            : null;

        $configNota = isset($this->colegio->id)
            ? configNota::where('colegio_id', $this->colegio->id)->first()
            : null;

        $this->nota_minima = $configNota->nota_minima ?? 1;
        $this->nota_maxima = $configNota->nota_maxima ?? 5;
    }
    public function render()
    {
        return view('livewire.docente.docente-notas');
    }
}
