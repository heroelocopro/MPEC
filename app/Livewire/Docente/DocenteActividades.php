<?php

namespace App\Livewire\Docente;

use App\Models\Actividad;
use App\Models\asignatura;
use App\Models\AsignaturaGrado;
use App\Models\asignaturaProfesor;
use App\Models\PeriodoAcademico;
use App\Models\Profesor;
use App\Notifications\NuevaActividadNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class DocenteActividades extends Component
{
    use WithFileUploads;

    public $asignaturas = [];
    public $grupos = [];
    public $actividades = [];
    public $colegio;
    public $profesor;


    public $mostrarFormulario = false;
    public $mostrarActividades = false;
    // variables de creacion
    public $profesor_id;
    public $asignatura_id;
    public $grupo_id;
    public $titulo;
    public $descripcion;
    public $fecha_entrega;
    public $archivo;
    // filtro
    public $grupoFiltro = '';
    public $gruposFiltro = [];


    public function verRespuestas($actividad_id)
    {

    }



    public function crearActividad()
    {
        $rules = [
            'profesor_id'     => 'required|exists:profesores,id',
            'asignatura_id'   => 'required|exists:asignaturas,id',
            'grupo_id'        => 'required|exists:grupos,id',
            'titulo'          => 'required|string|max:255',
            'descripcion'     => 'required|string|max:1000',
            'fecha_entrega'   => 'required|date|after_or_equal:today',
            'archivo'         => 'nullable|file|mimes:pdf,doc,docx,odt|max:10240',
        ];

        $this->validate($rules);

        $rutaArchivo = null;
        if ($this->archivo) {
            // Guarda el archivo en 'public/docnte/actividades' y obtiene la ruta
            $rutaArchivo = $this->archivo->store('docente/actividades', 'public');
        }

        $datos = [
            'profesor_id'     => $this->profesor_id,
            'asignatura_id'   => $this->asignatura_id,
            'grupo_id'        => $this->grupo_id,
            'titulo'          => $this->titulo,
            'descripcion'     => $this->descripcion,
            'fecha_entrega'   => $this->fecha_entrega,
            'archivo'         => $rutaArchivo, // solo guardas la ruta
            'periodo_id' => PeriodoAcademico::periodoActual($this->colegio->id)->id,
        ];

        try {
            $actividad = Actividad::create($datos);

            // Carga la relación 'usuario' de los estudiantes para evitar múltiples consultas
            $estudiantes = $actividad->grupo->estudiantes()->with('usuario')->get();

            foreach ($estudiantes as $estudiante) {
                // Verifica que la relación existe y es una instancia de User
                if ($estudiante->usuario instanceof \App\Models\User) {
                    $estudiante->usuario->notify(new \App\Notifications\NuevaActividadNotification($actividad));
                }
            }

            $this->dispatch('alerta', [
                'title' => 'Creación de actividad exitosa',
                'text' => '¡Se creó correctamente!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);

            $this->limpiarCrearActividad();
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Creación de actividad fallida',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }

    public function limpiarCrearActividad()
    {
        $this->reset(['asignatura_id','grupo_id','titulo','descripcion','fecha_entrega','archivo']);
    }



    public function updatedMostrarActividades()
    {
        $this->mostrarActividades = true;
        $this->mostrarFormulario = false;
        $this->cargarActividades();
    }
    public function updatedMostrarFormulario()
    {
        $this->mostrarFormulario = true;
        $this->mostrarActividades = false;
    }

    public function updatedAsignaturaId($value)
    {
        if($value != null || $value != '')
        {
            $gradoAsignatura = AsignaturaGrado::where('asignatura_id',$value)->get();
            $this->grupos = [];
            foreach($gradoAsignatura as $g)
            {
                foreach($g->grado->grupos as $grupo)
                {
                    array_push($this->grupos,$grupo);
                }
            }
        }
    }
    public function updatedGrupoFiltro($value)
    {

        if($value != null || $value != '')
        {
            $this->actividades = Actividad::where('profesor_id', $this->profesor_id)
            ->where('grupo_id',$value)
        ->with(['grupo', 'notas']) // carga todo lo necesario
        ->orderBy('grupo_id')
        ->get();
        }else{
            $this->actividades = [];
        }
    }

    public function cargarActividades()
    {
        $this->actividades = Actividad::where('profesor_id', $this->profesor_id)
        ->with(['grupo', 'notas']) // carga todo lo necesario
        ->orderBy('grupo_id')
        ->get();

        // foreach($this->actividades as $actividad)
        // {
        //     array_push($this->gruposFiltro,$actividad->grupo);
        // }
        $this->gruposFiltro = collect($this->actividades)
        ->pluck('grupo')       // obtiene todos los grupos
        ->unique('id')         // elimina los duplicados por ID
        ->values()             // reindexa
        ->all();               // convierte en array si lo necesitas




    }


    public function mount()
    {
        $this->mostrarFormulario = false;
        $this->mostrarActividades = false;
        $this->profesor = Profesor::where('user_id',Auth::user()->id)->first();
        $this->profesor_id = $this->profesor->id;
        $this->cargarActividades();
        $this->colegio = $this->profesor->colegio;
        $asignaturaProfesor = asignaturaProfesor::where('profesor_id',$this->profesor->id)->get();
        foreach($asignaturaProfesor as $asignatura)
        {
            array_push($this->asignaturas,$asignatura->asignatura);
        }
    }
    public function render()
    {
        return view('livewire.docente.docente-actividades');
    }
}
