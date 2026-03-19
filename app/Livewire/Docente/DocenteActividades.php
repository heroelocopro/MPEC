<?php

namespace App\Livewire\Docente;

use App\Models\Actividad;
use App\Models\asignatura;
use App\Models\AsignaturaGrado;
use App\Models\asignaturaProfesor;
use App\Models\PeriodoAcademico;
use App\Models\Profesor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class DocenteActividades extends Component
{
    use WithFileUploads;

    // ---------- propiedades públicas (inicializadas de forma segura) ----------
    public $asignaturas = [];      // array de objetos asignatura
    public $grupos = [];          // array de objetos grupo para el select
    public $actividades;          // Collection de actividades (puede ser collect())
    public $colegio;              // objeto colegio o object con id = null
    public $profesor;             // objeto Profesor o null

    public $mostrarFormulario = false;
    public $mostrarActividades = false;

    // variables de creación
    public $profesor_id = null;   // int|null
    public $asignatura_id = null;
    public $grupo_id = null;
    public $titulo = null;
    public $descripcion = null;
    public $fecha_entrega = null;
    public $archivo = null;

    // filtro
    public $grupoFiltro = '';
    public $gruposFiltro = [];    // array con grupos únicos extraídos de actividades

    // -------------------------------------------------------------------------

    public function verRespuestas($actividad_id)
    {
        // implementar según flujo (dejado vacío intencionalmente)
    }

    public function crearActividad()
    {
        // reglas de validación
        $rules = [
            'profesor_id'   => 'required|exists:profesores,id',
            'asignatura_id' => 'required|exists:asignaturas,id',
            'grupo_id'      => 'required|exists:grupos,id',
            'titulo'        => 'required|string|max:255',
            'descripcion'   => 'required|string|max:1000',
            'fecha_entrega' => 'required|date|after_or_equal:today',
            'archivo'       => 'nullable|file|mimes:pdf,doc,docx,odt|max:10240',
        ];

        $this->validate($rules);

        // obtener periodo actual de forma segura
        $periodo = null;
        if (!empty($this->colegio) && isset($this->colegio->id)) {
            $periodo = PeriodoAcademico::periodoActual($this->colegio->id);
        }

        if (!$periodo || !isset($periodo->id)) {
            // no hay periodo activo: abortamos con mensaje
            $this->dispatch('alerta', [
                'title' => 'Error',
                'text'  => 'No se encontró un periodo académico activo para el colegio.',
                'icon'  => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
            return;
        }

        $rutaArchivo = null;
        if ($this->archivo) {
            // guarda el archivo en storage/app/public/docente/actividades
            $rutaArchivo = $this->archivo->store('docente/actividades', 's3');
        }

        $datos = [
            'profesor_id'   => $this->profesor_id,
            'asignatura_id' => $this->asignatura_id,
            'grupo_id'      => $this->grupo_id,
            'titulo'        => $this->titulo,
            'descripcion'   => $this->descripcion,
            'fecha_entrega' => $this->fecha_entrega,
            'archivo'       => $rutaArchivo,
            'periodo_id'    => $periodo->id,
        ];


        try {
            $actividad = Actividad::create($datos);

            // notificar estudiantes del grupo (si existen)
            if ($actividad->grupo) {
                $estudiantes = $actividad->grupo->estudiantes()->with('usuario')->get();
                foreach ($estudiantes as $estudiante) {
                    if (!empty($estudiante->usuario) && method_exists($estudiante->usuario, 'notify')) {
                        $estudiante->usuario->notify(new \App\Notifications\NuevaActividadNotification($actividad));
                    }
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

            // recargo actividades para reflejar la nueva
            $this->cargarActividades();
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
        // resetea sólo los campos del formulario de creación
        $this->reset(['asignatura_id', 'grupo_id', 'titulo', 'descripcion', 'fecha_entrega', 'archivo']);
    }

    // ------------------ updated handlers ------------------

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
        // si value es vacío, limpiamos los grupos
        if (empty($value)) {
            $this->grupos = [];
            return;
        }

        // obtener los grados donde está la asignatura y luego sus grupos
        $gradoAsignaturas = AsignaturaGrado::where('asignatura_id', $value)->with('grado.grupos')->get();

        $this->grupos = [];
        foreach ($gradoAsignaturas as $ga) {
            if (isset($ga->grado) && isset($ga->grado->grupos)) {
                foreach ($ga->grado->grupos as $grupo) {
                    $this->grupos[] = $grupo;
                }
            }
        }

        // eliminar posibles duplicados (si hay)
        $this->grupos = collect($this->grupos)->unique('id')->values()->all();
    }

    public function updatedGrupoFiltro($value)
    {
        // si hay filtro, solo traemos las actividades de ese grupo; si no, recargamos todas
        if (!empty($value)) {
            $this->actividades = Actividad::where('profesor_id', $this->profesor_id)
                ->where('grupo_id', $value)
                ->with(['grupo', 'notas'])
                ->orderBy('grupo_id')
                ->get();
        } else {
            // recarga todas las actividades del profesor
            $this->cargarActividades();
        }

        // actualizar gruposFiltro para reflejar el set mostrado
        $this->gruposFiltro = collect($this->actividades)
            ->pluck('grupo')
            ->filter()
            ->unique('id')
            ->values()
            ->all();
    }

    // ------------------ carga de actividades ------------------

    public function cargarActividades()
    {
        // solo si profesor_id es válido
        $periodoActivoId = PeriodoAcademico::periodoActivo($this->colegio->id)->id;
        if (!empty($this->profesor_id)) {
            $this->actividades = Actividad::where('profesor_id', $this->profesor_id)
                ->where('periodo_id',$periodoActivoId)
                ->with(['grupo', 'notas'])
                ->orderBy('grupo_id')
                ->get();

            // extraer grupos únicos de las actividades (para filtro)
            $this->gruposFiltro = collect($this->actividades)
                ->pluck('grupo')
                ->filter()      // eliminar nulls por si acaso
                ->unique('id')
                ->values()
                ->all();
        } else {
            // valores seguros si no hay profesor
            $this->actividades = collect();
            $this->gruposFiltro = [];
        }
    }

    // ------------------ lifecycle mount ------------------

    public function mount()
    {
        // valores por defecto
        $this->mostrarFormulario = false;
        $this->mostrarActividades = false;

        $this->asignaturas = [];
        $this->grupos = [];
        $this->gruposFiltro = [];
        $this->actividades = collect();

        // obtener profesor según el usuario autenticado (si existe)
        $this->profesor = Profesor::where('user_id', Auth::id())->first();

        // profesor_id como int o null (nunca un objeto)
        $this->profesor_id = $this->profesor->id ?? null;

        // colegio seguro (objeto o fallback con id null)
        $this->colegio = $this->profesor->colegio ?? (object) ['id' => null];

        // cargar asignaturas del profesor (si tiene)
        if (!empty($this->profesor_id)) {
            $this->asignaturas = asignaturaProfesor::where('profesor_id', $this->profesor_id)
                ->with('asignatura')
                ->get()
                ->pluck('asignatura') // devuelve colección de asignaturas (puede incluir null)
                ->filter()            // eliminar nulls
                ->values()
                ->all();
        } else {
            $this->asignaturas = [];
        }

        // cargar actividades iniciales (si aplica)
        $this->cargarActividades();
    }

    public function render()
    {
        return view('livewire.docente.docente-actividades');
    }
}
