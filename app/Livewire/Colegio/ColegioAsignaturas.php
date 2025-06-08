<?php

namespace App\Livewire\Colegio;

use App\Models\asignatura;
use App\Models\Colegio;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ColegioAsignaturas extends Component
{
    // protected
    protected $listeners = [
        'eliminarAsignatura' => 'eliminarAsignatura',
    ];
    // modal Creacion
    public $modalCreacion;
    public $modalEdicion;
    // variables
    public $asignaturas = [];
    public $colegio;
    // Modelo Asignatura
    public $asignatura_id;
    public $nombre;
    public $area;
    public $descripcion;
    public $colegio_id;
    public $grado_minimo;
    public $grado_maximo;
    public $carga_horaria;
    public $tipo;
    public $estado;
    public $color;

    // cambios del modal

    public function abrirModalCreacion()
    {
        $this->modalCreacion = true;
    }

    public function abrirModalEdicion()
    {
        $this->modalEdicion = true;
    }




    // eliminar Asignatura
    public function eliminarAsignatura($id)
    {
        try {
            asignatura::findOrFail($id)->delete();
            $this->limpiar();
            $this->dispatch('alerta', [
                'title' => 'Exito al eliminar asignatura',
                'text' => 'se ha eliminado con exito!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Error al crear asignatura',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }
    // editar Asignatura
    public function cargarEditar($id)
    {
        $asignatura = asignatura::findOrFail($id);
        $this->nombre = $asignatura->nombre;
        $this->area = $asignatura->area;
        $this->descripcion = $asignatura->descripcion;
        $this->grado_minimo = $asignatura->grado_minimo;
        $this->grado_maximo = $asignatura->grado_maximo;
        $this->carga_horaria = $asignatura->carga_horaria;
        $this->tipo = $asignatura->tipo;
        if($asignatura->estado)
        {
            $this->estado = 'activo';
        }
        else{
            $this->estado = 'inactivo';
        }
        $this->color = $asignatura->color;
        $this->asignatura_id = $asignatura->id;
        $this->abrirModalEdicion();
    }
    public function editarAsignatura()
    {
        $this->validate([
            'nombre' => 'required|string|max:100',
            'area' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'colegio_id' => 'required|exists:colegios,id',
            'grado_minimo' => 'required|integer|min:0|max:11|lte:grado_maximo',
            'grado_maximo' => 'required|integer|min:0|max:11|gte:grado_minimo',
            'carga_horaria' => 'required|integer|min:1|max:50',
            'tipo' => 'required|in:obligatoria,optativa,extracurricular',
            'estado' => 'required|in:activo,inactivo',
            'color' => 'required|string|regex:/^#([a-fA-F0-9]{6})$/'
        ]);
            if($this->estado == 'activo')
            {
                $this->estado  = true;
            }else{
                $this->estado = false;
            }
                $datos = [
                    'nombre' => $this->nombre,
                    'area' => $this->area,
                    'descripcion' => $this->descripcion,
                    'colegio_id' => $this->colegio_id,
                    'grado_minimo' => $this->grado_minimo,
                    'grado_maximo' => $this->grado_maximo,
                    'carga_horaria' => $this->carga_horaria,
                    'tipo' => $this->tipo,
                    'estado' => $this->estado,
                    'color' => $this->color,

                ];
        try {
            $asignatura = asignatura::findOrfail($this->asignatura_id);
            $asignatura->update($datos);
            $this->limpiar();
            $this->dispatch('alerta', [
                'title' => 'Exito al editar asignatura',
                'text' => 'se ha editado con exito!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);

        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Error al editar asignatura',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }
    // crear Asignatura
    public function crearAsignatura()
    {
        $this->validate([
            'nombre' => 'required|string|max:100',
            'area' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'colegio_id' => 'required|exists:colegios,id',
            'grado_minimo' => 'required|integer|min:0|max:11|lte:grado_maximo',
            'grado_maximo' => 'required|integer|min:0|max:11|gte:grado_minimo',
            'carga_horaria' => 'required|integer|min:1|max:50',
            'tipo' => 'required|in:obligatoria,optativa,extracurricular',
            'estado' => 'required|in:activo,inactivo',
            'color' => 'required|string|regex:/^#([a-fA-F0-9]{6})$/'
        ]);

        // datos

        if($this->estado == 'activo')
        {
            $this->estado  = true;
        }else{
            $this->estado = false;
        }

        $datos = [
            'nombre' => $this->nombre,
            'area' => $this->area,
            'descripcion' => $this->descripcion,
            'colegio_id' => $this->colegio_id,
            'grado_minimo' => $this->grado_minimo,
            'grado_maximo' => $this->grado_maximo,
            'carga_horaria' => $this->carga_horaria,
            'tipo' => $this->tipo,
            'estado' => $this->estado,
            'color' => $this->color,

        ];


        // Aquí iría la lógica para guardar la asignatura
        try {
            Asignatura::create($datos);
            $this->limpiar();
            $this->dispatch('alerta', [
                'title' => 'Exito al crear asignatura',
                'text' => 'se ha creado con exito!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Error al crear asignatura',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }

    // limpiar
    public function limpiar()
    {
        $this->modalCreacion = false;
        $this->modalEdicion = false;
        $this->nombre = '';
        $this->area = '';
        $this->descripcion = '';
        $this->grado_minimo = 0;
        $this->grado_maximo = 0;
        $this->carga_horaria = '';
        $this->tipo = '';
        $this->estado = '';
        $this->color = '';
        $this->asignatura_id = 0;
        $this->cargarAsignaturas();
    }
    public function cargarAsignaturas()
    {
        $this->asignaturas = asignatura::where('colegio_id',$this->colegio_id)->get();
    }

    // mount se llama 1 vez antes del render carga valores
    public function mount()
    {
        $this->colegio = Colegio::where('user_id',Auth::user()->id)->first();
        $this->colegio_id = $this->colegio->id;
        $this->cargarAsignaturas();
        $this->modalCreacion = false;
        $this->modalEdicion = false;
    }
    // el render no debe llevar nada ya que cada cambio renderiza otra vez
    public function render()
    {
        return view('livewire.colegio.colegio-asignaturas');
    }
}
