<?php

namespace App\Livewire\Colegio;

use App\Models\Colegio;
use App\Models\Estudiante;
use App\Models\Grado;
use App\Models\matricula;
use App\Models\sedes_colegio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ColegioMatriculas extends Component
{
    protected $listeners = ['eliminarMatricula' => 'eliminarMatricula'];
    // editar
    public bool $modalEdicion = false;
    public matricula $matriculaEdicion;
    public $matriculaId;
    public $estudianteEdicion;
    public $estudiante_idEdicion;
    public $colegio_idEdicion;
    public $sede_idEdicion;
    public $grado_idEdicion;
    public $tipo_matriculaEdicion;
    public $estadoEdicion = 'activo'; // Valor por defecto
    public $fecha_matriculaEdicion;
    // Control del modal
    public bool $modalCreacion = false;

    // Propiedades relacionadas al estudiante
    public $estudiante;
    public $estudiante_id;

    // Propiedades de la matrícula
    public $colegio_id;
    public $sede_id;
    public $grado_id;
    public $tipo_matricula;
    public $estado = 'activo'; // Valor por defecto
    public $fecha_matricula;
    public $sortField = 'id'; // Campo por defecto para ordenar
    public $sortDirection = 'asc'; // Dirección por defecto
    public $paginacion = 5;
    public $buscador = '';
    public $gradoFilter = null;
    public $grados = [];

    // Reglas de validación
    protected function rules()
    {
        return [
            'estudiante_id' => 'required|exists:estudiantes,id',
            'colegio_id' => 'required|exists:colegios,id',
            'sede_id' => 'nullable|exists:sedes_colegios,id',
            'grado_id' => 'required|exists:grados,id',
            'tipo_matricula' => ['required', Rule::in(['nueva', 'renovacion', 'traslado'])],
            'estado' => ['required', Rule::in(['activo', 'completada', 'anulada'])],
            'fecha_matricula' => 'required|date',
        ];
    }

    // Mensajes de validación personalizados
    protected function messages()
    {
        return [
            'estudiante_id.required' => 'Debe seleccionar un estudiante',
            'tipo_matricula.in' => 'El tipo de matrícula no es válido',
            'sede_id' => 'sede',
            'grado_id' => 'grado',
            'tipo_matricula' => 'tipomatricula',
            'estado' => 'estado',
            'fecha_matricula' => 'fecha',

        ];
    }

    public function limpiar()
    {
        $this->estudiante = null;
        $this->grado_id = null;
        $this->tipo_matricula = null;
        $this->estudiante_id = null;
    }

    // Método para guardar la matrícula
    public function guardarMatricula()
    {
        $this->estudiante = Estudiante::findOrFail($this->estudiante_id);
        $this->colegio_id = $this->estudiante->colegio_id;
        $this->sede_id =  $this->estudiante->sede_id;
        $this->validate($this->rules(),$this->messages());
        try {
            $matricula = Matricula::create([
                'estudiante_id' => $this->estudiante_id,
                'colegio_id' => $this->colegio_id,
                'sede_id' => $this->sede_id,
                'grado_id' => $this->grado_id,
                'tipo_matricula' => $this->tipo_matricula,
                'estado' => $this->estado,
                'fecha_matricula' => $this->fecha_matricula,
            ]);


            $this->dispatch('alerta', [
            'title' => 'Creacion de matricula exitoso',
            'text' => '¡Se creo correctamente!',
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
            ]);

            // Resetear el formulario
            $this->modalCreacion = false;

            $this->limpiar();



        } catch (\Exception $e) {

            $this->dispatch('alerta', [
            'title' => 'Cambio de notas exitoso',
            'text' => $e->getMessage(),
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
        ]);
        }
    }
    public function cargarMatriculaEdicion($id)
    {
        $matricula = Matricula::with('estudiante')->findOrFail($id);
        $this->matriculaEdicion = $matricula;
        $this->matriculaId = $matricula->id;
        $this->estudiante_idEdicion = $matricula->estudiante_id;
        $this->estudianteEdicion = $matricula->estudiante->nombre_completo . ' - ' . $matricula->estudiante->documento;

        // Formatear fecha a 'Y-m-d' para que funcione en el input[type="date"]
        $this->fecha_matriculaEdicion = optional($matricula->fecha_matricula)->format('Y-m-d');

        $this->colegio_idEdicion = $matricula->colegio_id;
        $this->sede_idEdicion = $matricula->sede_id;
        $this->grado_idEdicion = $matricula->grado_id;
        $this->tipo_matriculaEdicion = $matricula->tipo_matricula;
        $this->estadoEdicion = $matricula->estado ?? 'activo';

        $this->modalEdicion = true;
    }

    public function limpiarEdicion()
    {
        $this->estudiante_idEdicion = null;
        $this->grado_idEdicion = null;
        $this->tipo_matriculaEdicion = null;
        $this->estadoEdicion = 'activo';
        $this->fecha_matriculaEdicion = null;
    }

    public function editarMatricula()
    {
        $this->validate([
            'grado_idEdicion' => 'required|exists:grados,id',
            'tipo_matriculaEdicion' => ['required', Rule::in(['nueva', 'renovacion', 'traslado'])],
            'fecha_matriculaEdicion' => 'required|date',
        ], [
            'grado_idEdicion.required' => 'Debe seleccionar un grado',
            'tipo_matriculaEdicion.required' => 'Seleccione un tipo de matrícula',
            'fecha_matriculaEdicion.required' => 'La fecha de matrícula es obligatoria',
        ]);

        try {
            $this->matriculaEdicion->update([
                'grado_id' => $this->grado_idEdicion,
                'tipo_matricula' => $this->tipo_matriculaEdicion,
                'fecha_matricula' => $this->fecha_matriculaEdicion,
            ]);

            $this->dispatch('alerta', [
                'title' => 'Actualización exitosa',
                'text' => 'La matrícula fue actualizada correctamente',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);

            $this->modalEdicion = false;

        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Error al actualizar',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }



    public function eliminarMatricula($id)
    {
        try {
            matricula::findOrFail($id)->delete();
            $this->dispatch('alerta', [
                'title' => 'Eliminacion de matricula exitosa!',
                'text' => '¡Se elimino correctamente!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
                ]);
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Eliminacion fallida',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
                ]);
        }
    }

    // Método para abrir el modal
    public function abrirModal()
    {
        $this->reset();
        $this->modalCreacion = true;
    }

    public function mount()
    {
        // Estamos base de las variables
        $this->modalCreacion = false;
        $this->fecha_matricula = now();
        $this->colegio_id = Colegio::where('user_id','=',Auth::user()->id)->first()->id;
        $this->sortField = 'id';
        $this->grados = Grado::where('colegio_id',$this->colegio_id)->get();

        $this->sortDirection = 'asc';
    }
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            // Si ya estamos ordenando por este campo, invertir la dirección
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Si es un campo nuevo, ordenar ascendente por defecto
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }
    public function render()
    {
        $colegio = Colegio::where('user_id','=',Auth::user()->id)->first();
        $sedes = sedes_colegio::where('colegio_id','=',$colegio->id)->get();
        $grados = Grado::where('colegio_id','=',$colegio->id)->orderBy('nivel')->get();
        $estudiantesSinMatricula = Estudiante::where('colegio_id', $colegio->id)
        ->whereDoesntHave('matricula', function($query) use ($colegio) {
            $query->where('colegio_id', $colegio->id);
        })
        ->orderBy('nombre_completo')  // Ordenar alfabéticamente
        ->get();
        $matriculas = Matricula::where('colegio_id', $colegio->id)
        ->whereHas('estudiante', function ($query) {
            $query->where(function ($q) {
                $q->where('nombre_completo', 'like', '%' . $this->buscador . '%')
                ->orWhere('documento', 'like', '%' . $this->buscador . '%')
                ->orWhere('id', $this->buscador);
            });

            if (!empty($this->gradoFilter)) {
                $query->where('grado_id', 'like', '%' . $this->gradoFilter . '%');
            }
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->paginacion);


        return view('livewire.colegio.colegio-matriculas',compact('matriculas','sedes','grados','estudiantesSinMatricula','colegio'));
    }
}
