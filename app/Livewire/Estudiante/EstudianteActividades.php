<?php

namespace App\Livewire\Estudiante;

use App\Models\Actividad;
use App\Models\asignatura;
use App\Models\AsignaturaGrado;
use App\Models\Estudiante;
use App\Models\EstudianteGrupo;
use App\Models\respuesta_actividad;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class EstudianteActividades extends Component
{
    // informacion actividades
    public $archivo;
    public $contenido;
    public $actividad_id;
    public $archivoGuardado;
    public $verModalActividad = false;
    public $cargandoSubirActividad = false;
    use WithFileUploads;

    protected $rulesActivity = [
        'contenido' => 'required|string',
        'archivo' => 'nullable|file|max:5120', // 5MB máximo por ejemplo
    ];
    public function cargarSubirActividad($actividad_id)
    {
        // Paso 1: Mostrar spinner, modal cerrado aún
        $this->cargandoSubirActividad = true;
        $this->verModalActividad = false;

        // Paso 2: Cargar datos
        $this->actividad_id = $actividad_id;
        $respuesta = respuesta_actividad::where('actividad_id', $actividad_id)
            ->where('estudiante_id', $this->estudiante->id)
            ->first();

        if ($respuesta) {
            $this->contenido = $respuesta->contenido;
            $this->archivoGuardado = $respuesta->archivo;
        } else {
            $this->contenido = '';
            $this->archivoGuardado = null;
        }

        // Paso 3: Apagar spinner y mostrar modal
        $this->cargandoSubirActividad = false;
        $this->verModalActividad = true;
    }




    public function guardarRespuesta()
    {
        $this->validate($this->rulesActivity);
        try {
            $respuesta = respuesta_actividad::updateOrCreate(
                ['actividad_id' => $this->actividad_id, 'estudiante_id' => $this->estudiante->id],
                ['contenido' => $this->contenido]
            );

            if ($this->archivo) {
                // Guardar archivo y actualizar campo
                $path = $this->archivo->store('respuestas_actividades','public');
                $respuesta->archivo = $path;
                $respuesta->save();

                $this->archivoGuardado = $path;
                $this->archivo = null; // limpiar input file
            }
            $this->dispatch('alerta', [
                'title' => 'Actividad subida',
                'text' => '¡Se subio correctamente!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
            $this->verModalActividad = false;
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Actividad Error',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }

    }

    // abrir modal
    public $verModal = false;
    public $actividadModal;
    public $cargandoActividad = false;


    public function updatedVerModal($value)
    {
        if($value == false)
        {
            $this->actividadModal = null;
        }
    }
    public function cargarActividad($id)
    {
        $this->cargandoActividad = true;
        $this->verModal = false;
        // Simula carga
        $this->actividadModal = Actividad::with('asignatura')->find($id);

        $this->cargandoActividad = false;
        $this->verModal = true;
    }

    // datos basicos del colegio,estudiante
    public $colegio;
    public $estudiante;
    public $matricula;
    public $grado;
    public $grupo;
    public $asignaturas = [];
    public $actividadesAsignaturas = [
        ['nombre','actividades'],
    ];
    // ['nombre' => 'Matemáticas', 'actividades' => [Actividad, Actividad, ...]],
    public function cargarActividades()
    {
        $grupoFiltrado = $this->grupo->id;

        foreach ($this->asignaturas as $index => $asignatura) {
            // Filtrar actividades por grupo_id y fecha_entrega mayor o igual a hoy
            $actividadesFiltradas = $asignatura->actividades
                ->filter(function ($actividad) use ($grupoFiltrado) {
                    return $actividad->grupo_id === $grupoFiltrado &&
                        Carbon::now()->lte(Carbon::parse($actividad->fecha_entrega));
                })
                ->sortBy('fecha_entrega')
                ->values(); // opcional, para resetear índices

            // Guardar nombre de la asignatura y sus actividades filtradas
            $this->actividadesAsignaturas[$index]['0'] = $asignatura->nombre;
            $this->actividadesAsignaturas[$index]['1'] = $actividadesFiltradas;
        }
    }


    public function cargarAsignaturas()
    {
        // obtenemos todas las asignaturas del grado del estudiante
        $asignaturasGrados = AsignaturaGrado::where('grado_id',$this->grado->id)->get();
        foreach($asignaturasGrados as $asignatura)
        {
            array_push($this->asignaturas,$asignatura->asignatura);
        }
    }
    public function mount()
    {
        // datos basicos del estudiante
        $this->estudiante = Estudiante::where('user_id',Auth::user()->id)->first();
        $this->colegio = $this->estudiante->colegio;
        $this->matricula = $this->estudiante->matricula;
        $this->grado = $this->matricula->grado;
        $this->grupo = EstudianteGrupo::where('estudiante_id',$this->estudiante->id)->first()->grupo;
        // cargar metodos
        $this->cargarAsignaturas();
        $this->cargarActividades();


    }
    public function render()
    {
        return view('livewire.estudiante.estudiante-actividades');
    }
}
