<?php

namespace App\Livewire\Colegio;

use App\Models\Colegio;
use App\Models\Estudiante;
use App\Models\sedes_colegio;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ColegioEstudiantes extends Component
{
    // valores importantes para la eliminacion
    protected $listeners = [
        'EliminarEstudiante' => 'EliminarEstudiante'
    ];

    public function EliminarEstudiante($id)
    {
        try {
            $estudiante = Estudiante::findOrFail($id)->usuario->delete();
            $this->dispatch('alerta', [
            'title' => 'Eliminacion de Estudiante exitoso',
            'text' => '¡Se elimino correctamente!',
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
            'title' => 'Eliminacion de Estudiante fallida',
            'text' => $th->getMessage(),
            'icon' => 'error',
            'toast' => true,
            'position' => 'top-end',
            ]);
        }
    }
    // valores importantes para la edicion
    public $estudianteEdicion;
    public $sede_idEdicion;
    public $nombre_completoEdicion;
    public $documentoEdicion;
    public $tipo_documentoEdicion;
    public $fecha_nacimientoEdicion;
    public $generoEdicion;
    public $grupo_sanguineoEdicion;
    public $epsEdicion;
    public $sisbenEdicion;
    public $poblacion_vulnerableEdicion;
    public $discapacidadEdicion;
    public $direccionEdicion;
    public $telefonoEdicion;
    public $correoEdicion;
    public $modalEdicion = false;
    public $totalStepsEdicion=4;
    public $currentStepEdicion=1;

    public function editarEstudiante()
    {
        try {

            $datos = [
                'colegio_id' => $this->colegio_id,
                'sede_id' => $this->sede_idEdicion ?: null,
                'nombre_completo' => $this->nombre_completoEdicion,
                'documento' => $this->documentoEdicion,
                'tipo_documento' => $this->tipo_documentoEdicion,
                'fecha_nacimiento' => $this->fecha_nacimientoEdicion,
                'genero' => $this->generoEdicion,
                'grupo_sanguineo' => $this->grupo_sanguineoEdicion,
                'eps' => $this->epsEdicion,
                'sisben' => $this->sisbenEdicion,
                'poblacion_vulnerable' => $this->poblacion_vulnerableEdicion,
                'discapacidad' => $this->discapacidadEdicion,
                'direccion' => $this->direccionEdicion,
                'telefono' => $this->telefonoEdicion,
                'correo' => $this->correoEdicion,
            ];

            $this->estudianteEdicion->update($datos);

            $this->dispatch('alerta', [
            'title' => 'Edicion de Estudiante exitoso',
            'text' => '¡Se edito correctamente!',
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
            ]);
            $this->modalEdicion = false;
            $this->currentStepEdicion = 1;
        } catch (\Throwable $th) {
           $this->dispatch('alerta', [
            'title' => 'Edicion de Estudiante fallida',
            'text' => $th->getMessage(),
            'icon' => 'error',
            'toast' => true,
            'position' => 'top-end',
            ]);
            $this->modalCreacion = false;
        }
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
            case 1: // Información Básica
                $rules = [
                    'nombre_completoEdicion' => 'required|string|max:255',
                    'tipo_documentoEdicion' => 'required|string|in:RC,TI,CC,TE,CE,NIT,PP,PEP,DIE',
                    'documentoEdicion' => 'required|string|max:255|unique:estudiantes,documento,' . $this->estudianteEdicion->id,
                    'fecha_nacimientoEdicion' => 'required|date',
                    'generoEdicion' => 'nullable|string|in:masculino,femenino,otro,prefiero_no_decir',
                ];
                break;

            case 2: // Información de Contacto
                $rules = [
                    'direccionEdicion' => 'nullable|string|max:255',
                    'telefonoEdicion' => 'required|string|max:20',
                    'correoEdicion' => 'nullable|email|max:255|unique:estudiantes,documento,' . $this->estudianteEdicion->id,
                ];
                break;

            case 3: // Información de Salud
                $rules = [
                    'grupo_sanguineoEdicion' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                    'epsEdicion' => 'nullable|string|in:COOMEVA,CAFESALUD,COMPENSAR,SALUDTOTAL,SANCOR,EPS SANITAS,EPS SURAMERICANA,EMSSANAR,EPS SALUD PUBLICA,CRUZ BLANCA',
                    'sisbenEdicion' => 'nullable|string|in:A1,A2,A3,A4,A5,B1,B2,B3,B4,B5,B6,B7,C1,C2,C3,C4,C5,C6,C7,C8,C9,C10,C11,C12,C13,C14,C15,C16,C17,C18,D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21',
                    'poblacion_vulnerableEdicion' => 'nullable|string|in:Pobreza Extrema,Pobreza Moderada,Desplazados por la Violencia,Niños, Niñas y Adolescentes,Personas con Discapacidad,Comunidades Indígenas,Afrocolombianos,Víctimas del Conflicto Armado,Personas LGTBI,Víctimas de Desastres Naturales,Mujeres Víctimas de Violencia de Género,Adultos Mayores,Otros,No reporta población vulnerable',
                    'discapacidadEdicion' => 'nullable|string|in:Visual,Auditiva,Física / Motora,Intelectual,Psicosocial,Múltiple,Sin Discapacidad',

                ];
                break;

            case 4: // Información Académica
                $rules = [
                    'colegio_idEdicion' => 'required|exists:colegios,id',
                    'sede_idEdicion' => 'nullable|exists:sedes_colegios,id',
                ];
                break;

        }


        $this->validate($rules);
    }

    public function cargarEstudiante($id)
    {
        $this->estudianteEdicion = Estudiante::findOrFail($id);
        $this->sede_idEdicion = $this->estudianteEdicion->sede_id;
        $this->nombre_completoEdicion = $this->estudianteEdicion->nombre_completo;
        $this->documentoEdicion = $this->estudianteEdicion->documento;
        $this->tipo_documentoEdicion = $this->estudianteEdicion->tipo_documento;
        $this->fecha_nacimientoEdicion = $this->estudianteEdicion->fecha_nacimiento;
        $this->generoEdicion = $this->estudianteEdicion->genero;
        $this->grupo_sanguineoEdicion = $this->estudianteEdicion->grupo_sanguineo;
        $this->epsEdicion = $this->estudianteEdicion->eps;
        $this->sisbenEdicion = $this->estudianteEdicion->sisben;
        $this->poblacion_vulnerableEdicion = $this->estudianteEdicion->poblacion_vulnerable;
        $this->discapacidadEdicion = $this->estudianteEdicion->discapacidad;
        $this->direccionEdicion = $this->estudianteEdicion->direccion;
        $this->telefonoEdicion = $this->estudianteEdicion->telefono;
        $this->correoEdicion = $this->estudianteEdicion->correo;
        $this->modalEdicion = true;
        $this->currentStepEdicion = 1;
    }
    // valores importantes para la creacion
    public $colegio_id;
    public $sede_id;
    public $nombre_completo;
    public $documento;
    public $tipo_documento;
    public $fecha_nacimiento;
    public $genero;
    public $grupo_sanguineo;
    public $eps;
    public $sisben;
    public $poblacion_vulnerable;
    public $discapacidad;
    public $direccion;
    public $telefono;
    public $correo;

    public function limpiar()
    {
        $this->sede_id = null;
        $this->nombre_completo = null;
        $this->documento = null;
        $this->tipo_documento = null;
        $this->fecha_nacimiento = null;
        $this->genero = null;
        $this->grupo_sanguineo = null;
        $this->eps = null;
        $this->sisben = null;
        $this->poblacion_vulnerable = null;
        $this->discapacidad = null;
        $this->direccion = null;
        $this->telefono = null;
        $this->correo = null;
        $this->currentStep = 1;
    }

    public function crearEstudiante()
    {
        try {
            $datos = [
                'colegio_id' => $this->colegio_id,
                'sede_id' => $this->sede_id,
                'nombre_completo' => $this->nombre_completo,
                'documento' => $this->documento,
                'tipo_documento' => $this->tipo_documento,
                'fecha_nacimiento' => $this->fecha_nacimiento,
                'genero' => $this->genero,
                'grupo_sanguineo' => $this->grupo_sanguineo,
                'eps' => $this->eps,
                'sisben' => $this->sisben,
                'poblacion_vulnerable' => $this->poblacion_vulnerable,
                'discapacidad' => $this->discapacidad,
                'direccion' => $this->direccion,
                'telefono' => $this->telefono,
                'correo' => $this->correo,
            ];
            Estudiante::create($datos);
            $this->dispatch('alerta', [
            'title' => 'Creacion de Estudiante exitoso',
            'text' => '¡Se creo correctamente!',
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
            ]);
            $this->limpiar();
            $this->modalCreacion = false;
        } catch (\Throwable $th) {
            $data = [
            'title' => 'Error al crear estudiante!',
            'text' => $th->getMessage(),
            'icon' => 'error'
            ];
            $this->dispatch('alerta',$data);
            $this->modalCreacion = false;
        }
    }

    // valores importantes para el modal creacion
    public $totalSteps=4;
    public $currentStep=1;
    public $modalCreacion = false;

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
            case 1: // Información Básica
                $rules = [
                    'nombre_completo' => 'required|string|max:255',
                    'tipo_documento' => 'required|string|in:RC,TI,CC,TE,CE,NIT,PP,PEP,DIE',
                    'documento' => 'required|string|max:255|unique:estudiantes,documento',
                    'fecha_nacimiento' => 'required|date',
                    'genero' => 'nullable|string|in:masculino,femenino,otro,prefiero_no_decir',
                ];
                break;

            case 2: // Información de Contacto
                $rules = [
                    'direccion' => 'nullable|string|max:255',
                    'telefono' => 'required|string|max:20',
                    'correo' => 'nullable|email|max:255',
                ];
                break;

            case 3: // Información de Salud
                $rules = [
                    'grupo_sanguineo' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                    'eps' => 'nullable|string|in:COOMEVA,CAFESALUD,COMPENSAR,SALUDTOTAL,SANCOR,EPS SANITAS,EPS SURAMERICANA,EMSSANAR,EPS SALUD PUBLICA,CRUZ BLANCA',
                    'sisben' => 'nullable|string|in:A1,A2,A3,A4,A5,B1,B2,B3,B4,B5,B6,B7,C1,C2,C3,C4,C5,C6,C7,C8,C9,C10,C11,C12,C13,C14,C15,C16,C17,C18,D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21',
                    'poblacion_vulnerable' => 'nullable|string|in:Pobreza Extrema,Pobreza Moderada,Desplazados por la Violencia,Niños, Niñas y Adolescentes,Personas con Discapacidad,Comunidades Indígenas,Afrocolombianos,Víctimas del Conflicto Armado,Personas LGTBI,Víctimas de Desastres Naturales,Mujeres Víctimas de Violencia de Género,Adultos Mayores,Otros,No reporta población vulnerable',
                    'discapacidad' => 'nullable|string|in:Visual,Auditiva,Física / Motora,Intelectual,Psicosocial,Múltiple,Sin Discapacidad',

                ];
                break;

            case 4: // Información Académica
                $rules = [
                    'colegio_id' => 'required|exists:colegios,id',
                    'sede_id' => 'nullable|exists:sedes_colegios,id',
                ];
                break;

        }


        $this->validate($rules);
    }

    // valores importantes para filtro
    use WithPagination;
    public $paginacion;
    public $buscador;
    public $sortField = 'id'; // Campo por defecto para ordenar
    public $sortDirection = 'asc'; // Dirección por defecto
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
        $colegio = Colegio::where('user_id', Auth::id())->first();

        if ($colegio) {
            $this->colegio_id = $colegio->id;
        } else {
            $sede = sedes_colegio::where('user_id', Auth::id())->first();
            $this->colegio_id = $sede?->colegio->id;
            $this->sede_id = $sede->id;
        }
            }
            public function render()
            {
                $usuarioId = Auth::id();

                // Detectar si el usuario es del colegio principal
                $colegio = Colegio::where('user_id', $usuarioId)->first();

                // O si es de una sede
                $sede = sedes_colegio::where('user_id', $usuarioId)->first();

                if ($colegio) {
                    $this->colegio_id = $colegio->id;
                    $colegioId = $colegio;

                    // El colegio principal ve todos los estudiantes del colegio
                    $estudiantes = Estudiante::query()
                        ->where('colegio_id', $this->colegio_id)
                        ->when($this->buscador, function ($query) {
                            $query->where(function ($subquery) {
                                $subquery->where('nombre_completo', 'like', '%' . $this->buscador . '%')
                                    ->orWhere('documento', 'like', '%' . $this->buscador . '%');
                            });
                        })
                        ->orderBy($this->sortField, $this->sortDirection)
                        ->paginate($this->paginacion);
                } elseif ($sede) {
                    $this->colegio_id = $sede->colegio_id;
                    $colegioId = $sede;

                    // La sede solo ve sus propios estudiantes
                    $estudiantes = Estudiante::query()
                        ->where('colegio_id', $this->colegio_id)
                        ->where('sede_id', $sede->id)
                        ->when($this->buscador, function ($query) {
                            $query->where(function ($subquery) {
                                $subquery->where('nombre_completo', 'like', '%' . $this->buscador . '%')
                                    ->orWhere('documento', 'like', '%' . $this->buscador . '%');
                            });
                        })
                        ->orderBy($this->sortField, $this->sortDirection)
                        ->paginate($this->paginacion);
                } else {
                    // En caso de que no sea ni del colegio ni de una sede, devolver vacío
                    $estudiantes = collect();
                    $colegioId = null;
                }

                return view('livewire.colegio.colegio-estudiantes', compact('estudiantes', 'colegioId'));
            }

}
