<?php

namespace App\Livewire\Docente;

use App\Models\asignatura;
use App\Models\AsignaturaGrado;
use App\Models\asignaturaProfesor;
use App\Models\Asistencia;
use App\Models\EstudianteGrupo;
use App\Models\Grupo;
use App\Models\Profesor;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class DocenteAsistencias extends Component
{
    public $errores = [];

    public $colegio;
    public $profesor;

    public $asignaturas = [];
    public $asignatura;
    public $asignatura_id;

    public $grados = [];
    public $grupos = [];
    public $grupo;
    public $grupo_id;

    public $estudiantes = [];

    // array principal
    public $asistencias = [];

    public function mount()
    {
        $this->profesor = Profesor::where('user_id', Auth::id())->first() ?? (object)[];
        $this->colegio = $this->profesor->colegio ?? (object)['id' => null];

        if ($this->profesor->id ?? false) {
            $aps = asignaturaProfesor::where('profesor_id', $this->profesor->id)->get();
            foreach ($aps as $ap) {
                if ($ap->asignatura) {
                    $this->asignaturas[] = $ap->asignatura;
                }
            }
        }
    }

    /* =========================
       GUARDAR ASISTENCIAS
    ==========================*/
    public function guardarAsistencias()
{
    $this->errores = [];

    foreach ($this->asistencias as $key => $asistencia) {

        $estudiante_id = (int) str_replace('est_', '', $key);

        $data = [
            'estudiante_id' => $estudiante_id,
            'grupo_id' => $this->grupo->id,
            'colegio_id' => $this->colegio->id,
            'asignatura_id' => $this->asignatura->id,
            'profesor_id' => $this->profesor->id,
            'estado' => $asistencia['estado'] ?? null,
            'justificacion' => $asistencia['justificacion'] ?? null,
        ];

        // ✅ VALIDACIÓN CORRECTA
        $validator = Validator::make($data, [
            'estudiante_id' => 'required|exists:estudiantes,id',
            'grupo_id' => 'required|exists:grupos,id',
            'colegio_id' => 'required|exists:colegios,id',
            'asignatura_id' => 'required|exists:asignaturas,id',
            'profesor_id' => 'required|exists:profesores,id',
            'estado' => 'required|in:presente,ausente,tarde,justificado',
            'justificacion' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            $this->errores[$estudiante_id] = $validator->errors()->all();
            continue;
        }

        try {
            Asistencia::updateOrCreate(
                [
                    'estudiante_id' => $estudiante_id,
                    'fecha' => now()->toDateString(),
                    'bloque' => now()->format('H:i'),
                ],
                [
                    'grupo_id' => $data['grupo_id'],
                    'colegio_id' => $data['colegio_id'],
                    'asignatura_id' => $data['asignatura_id'],
                    'profesor_id' => $data['profesor_id'],
                    'estado' => $data['estado'],
                    'justificacion' => $data['justificacion'],
                ]
            );
        } catch (\Throwable $e) {
            $this->errores[$estudiante_id] = $e->getMessage();
        }
    }

    $this->dispatch('alerta', [
        'title' => count($this->errores) ? 'Errores' : 'Asistencias Registradas',
        'text' => count($this->errores)
            ? 'Se detectaron ' . count($this->errores) . ' errores'
            : 'Se guardó correctamente',
        'icon' => count($this->errores) ? 'error' : 'success',
        'toast' => true,
        'position' => 'top-end',
    ]);
}


    /* =========================
       CARGAR ASISTENCIAS DEL DÍA
    ==========================*/
    public function cargarAsistenciasDelDia()
    {
        $this->asistencias = [];

        $asistencias = Asistencia::whereDate('fecha', now()->today())
            ->where('colegio_id', $this->colegio->id)
            ->where('profesor_id', $this->profesor->id)
            ->where('grupo_id', $this->grupo->id)
            ->where('asignatura_id', $this->asignatura->id)
            ->get();

        foreach ($asistencias as $a) {
            $this->asistencias['est_'.$a->estudiante_id] = [
                'estado' => $a->estado,
                'justificacion' => $a->justificacion,
            ];
        }
    }

    /* =========================
       SELECTS
    ==========================*/
    public function updatedAsignaturaId($value)
    {
        $this->asignatura = asignatura::find($value);
        $this->grados = [];
        $this->grupos = [];
        $this->grupo = null;
        $this->grupo_id = null;
        $this->estudiantes = [];
        $this->asistencias = [];

        if (!$value) return;

        $ags = AsignaturaGrado::where('asignatura_id', $value)->get();
        foreach ($ags as $ag) {
            $this->grados[] = $ag->grado;
        }

        foreach ($this->grados as $g) {
            foreach ($g->grupos as $grupo) {
                $this->grupos[] = $grupo;
            }
        }
    }

    public function updatedGrupoId($value)
    {
        $this->estudiantes = [];
        $this->asistencias = [];

        if (!$value) return;

        $this->grupo = Grupo::findOrFail($value);

        $egs = EstudianteGrupo::where('grupo_id', $value)->get();
        foreach ($egs as $eg) {
            $this->estudiantes[] = $eg->estudiante;
        }

        $this->cargarAsistenciasDelDia();
    }

    public function render()
    {
        return view('livewire.docente.docente-asistencias');
    }
}
