<?php

namespace App\Livewire\Colegio;

use App\Models\Colegio;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ColegioDocentes extends Component
{
    // esucchadores
    protected $listeners = [
        'EliminarProfesor' => 'EliminarProfesor'
    ];
    // valores importantes del profesor para la creacion
    public $colegio;
    public $colegio_id;
    public $sede_id;
    public $nombre_completo;
    public $documento;
    public $tipo_documento;
    public $correo;
    public $telefono;
    public $titulo_academico;

    // valores importantes del profesor para la edicion
    public $profesorEdicion;
    public $colegioEdicion;
    public $colegio_idEdicion;
    public $sede_idEdicion;
    public $nombre_completoEdicion;
    public $documentoEdicion;
    public $tipo_documentoEdicion;
    public $correoEdicion;
    public $telefonoEdicion;
    public $titulo_academicoEdicion;


    // valores importantes para filtro
    use WithPagination;
    public $paginacion;
    public $buscador;
    public $sortField = 'id'; // Campo por defecto para ordenar
    public $sortDirection = 'asc'; // Dirección por defecto

    // valores importantes modal creacion
    public $modalCreacion;
    public $currentStep;
    public $totalSteps;

    // valores importantes modal edicion
    public $modalEdicion;
    public $currentStepEdicion;
    public $totalStepsEdicion;

    public function EliminarProfesor($id)
    {
        try {
            Profesor::findOrFail($id)->usuario->delete();
        $data = [
            'title' => 'Profesor Eliminado!',
            'text' => 'se elimino con exito',
            'icon' => 'success'
            ];
            $this->dispatch('alerta',$data);
        } catch (\Throwable $th) {
             $data = [
            'title' => 'Error',
            'text' => 'No se pudo eliminar el profesor',
            'icon' => 'error'
            ];
            $this->dispatch('alerta',$data);
        }


    }

    public function editarProfesor()
    {

        try {
            $this->profesorEdicion->update([
                'colegio_id'        => $this->colegio_idEdicion,
                'sede_id' => $this->sede_idEdicion === '' ? null : $this->sede_idEdicion,
                'nombre_completo'   => $this->nombre_completoEdicion,
                'documento'         => $this->documentoEdicion,
                'tipo_documento'    => $this->tipo_documentoEdicion,
                'correo'            => $this->correoEdicion,
                'telefono'          => $this->telefonoEdicion,
                'titulo_academico'  => $this->titulo_academicoEdicion,
            ]);


            $data = [
            'title' => 'Profesor Editado!',
            'text' => 'se actualizo con exito',
            'icon' => 'success'
            ];
            $this->modalEdicion = false;
            $this->dispatch('alerta',$data);
            $this->limpiarEditar();
        } catch (\Throwable $th) {
             $data = [
            'title' => 'Error al editar Profesor!',
            'text' => 'algo salio mal',
            'icon' => 'error'
            ];
            $this->dispatch('alerta',$data);
        }
    }

    public function limpiarEditar()
    {
        $this->currentStepEdicion = 1;
    }

    public function cargarProfesor($id)
    {
        $this->profesorEdicion = Profesor::findOrFail($id);
        // $this->colegioEdicion = $this->profesorEdicion->colegio_id;
        $this->colegio_idEdicion = $this->profesorEdicion->colegio_id;
        $this->sede_idEdicion = $this->profesorEdicion->sede_id;
        $this->nombre_completoEdicion = $this->profesorEdicion->nombre_completo;
        $this->documentoEdicion = $this->profesorEdicion->documento;
        $this->tipo_documentoEdicion = $this->profesorEdicion->tipo_documento;
        $this->correoEdicion = $this->profesorEdicion->correo;
        $this->telefonoEdicion = $this->profesorEdicion->telefono;
        $this->titulo_academicoEdicion = $this->profesorEdicion->titulo_academico;
        $this->modalEdicion = true;
    }
    public function nextStepEdicion()
    {
        $this->validateCurrentStepEdicion();
        $this->currentStepEdicion++;
    }

    public function prevStepEdicion()
    {
        $this->currentStepEdicion--;
    }

    protected function validateCurrentStepEdicion()
    {
        $rules = [];

        switch ($this->currentStepEdicion) {
            case 1:
                $rules = [
                    'nombre_completoEdicion' => 'required|string|max:255',
                    'tipo_documentoEdicion' => 'required|string|in:RC,TI,CC,TE,CE,NIT,PP,PEP,DIE',
                    'documentoEdicion' => ['required','string','max:255',Rule::unique('profesores', 'correo')->ignore($this->profesorEdicion->id),],
                ];
                break;
            case 2:
                $rules = [
                    'correoEdicion' => ['required','email','max:255',Rule::unique('profesores', 'correo')->ignore($this->profesorEdicion->id),],
                    'telefonoEdicion' => 'nullable|string|max:20',
                    'titulo_academicoEdicion' => 'nullable|string|max:255',
                ];
                break;
            case 3:
                $rules = [
                    'colegio_idEdicion' => 'required|exists:colegios,id',
                    'sede_idEdicion' => 'nullable|exists:sedes_colegios,id',
                ];
                break;
        }

        $this->validate($rules);
    }

    public function crearProfesor()
    {
        try {
            $this->validateCurrentStep();

            $profesor = [
                'colegio_id' => $this->colegio_id,
                'sede_id' => $this->sede_id,
                'nombre_completo' => $this->nombre_completo,
                'documento' => $this->documento,
                'tipo_documento' => $this->tipo_documento,
                'correo' => $this->correo,
                'telefono' => $this->telefono,
                'titulo_academico' => $this->titulo_academico,
            ];
            Profesor::create($profesor);
            $this->dispatch('alerta', [
            'title' => 'Creacion de Profesor exitoso',
            'text' => '¡Se guardó correctamente!',
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
        ]);
            $this->modalCreacion = false;
            $this->limpiar();

        } catch (\Throwable $th) {
            $data = [
                        'title' => 'Fallo al Profesor Creado!',
                        'text' => $th->getMessage(),
                        'icon' => 'error'
                    ];
            $this->dispatch('alerta',$data);
        }
    }
    public function limpiar()
    {
        $this->currentStep = 1;
        $this->nombre_completo = null;
        $this->documento = null;
        $this->tipo_documento = null;
        $this->correo = null;
        $this->telefono = null;
        $this->titulo_academico = null;
    }

        public function nextStep()
    {
        $this->validateCurrentStep();
        $this->currentStep++;
    }

    public function prevStep()
    {
        $this->currentStep--;
    }
        protected function validateCurrentStep()
    {
        $rules = [];

        switch ($this->currentStep) {
            case 1:
                $rules = [
                    'nombre_completo' => 'required|string|max:255',
                    'tipo_documento' => 'required|string|in:RC,TI,CC,TE,CE,NIT,PP,PEP,DIE',
                    'documento' => 'required|unique:profesores,documento|string|max:255',
                ];
                break;
            case 2:
                $rules = [
                    'correo' => 'required|email|max:255|unique:profesores,correo', // Ejemplo si es para profesores
                    'telefono' => 'nullable|string|max:20',
                    'titulo_academico' => 'nullable|string|max:255',
                ];
                break;
            case 3:
                $rules = [
                    'colegio_id' => 'required|exists:colegios,id',
                    'sede_id' => 'nullable|exists:sedes_colegios,id',
                ];
                break;
        }

        $this->validate($rules);
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
    public function mount()
    {
        $this->paginacion = 5;

        $this->buscador = '';

        $this->sortField = 'id';

        $this->sortDirection = 'asc';

        $this->modalCreacion = false;

        $this->currentStep = 1;

        $this->totalSteps = 3;

        $this->colegio = Colegio::where('user_id','=',Auth::user()->id)->first();

        $this->colegio_id = $this->colegio->id;

        $this->sede_idEdicion = null;

        $this->modalEdicion = false;

        $this->currentStepEdicion = 1;

        $this->totalStepsEdicion = 3;


    }
    public function render()
    {
        $colegioId = Colegio::where('user_id','=',Auth::user()->id)->first();
        $profesores = Profesor::query()
        ->where('colegio_id', $colegioId->id)
        ->where(function($query) {
            $query->where('nombre_completo', 'like', '%'.$this->buscador.'%')
                ->orWhere('correo', 'like', '%'.$this->buscador.'%')
                ->orWhere('documento', 'like', '%'.$this->buscador.'%');
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->paginacion);
        return view('livewire.colegio.colegio-docentes', compact('profesores'));
    }
}
