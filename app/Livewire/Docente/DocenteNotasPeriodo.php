<?php

namespace App\Livewire\Docente;

use App\Models\AsignaturaGrado;
use App\Models\asignaturaProfesor;
use App\Models\Grupo;
use App\Models\NotaFinal;
use App\Models\PeriodoAcademico;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DocenteNotasPeriodo extends Component
{
    // periodos y grupo
    public $periodoSeleccionado;
    public $grupoSeleccionado;
    public $notaFinalAsignaturas = [];
    public $promediosEstudiantes = [];

    public $asignaturas = [];
    public $estudiantes;

    public $notasPorEstudiante = [];

        public function obtenerAsignaturasDelProfesorEnGrupo($grupoId)
    {
        $asignaturasIdsDelProfesor = asignaturaProfesor::where('profesor_id', $this->profesor->id)
            ->pluck('asignatura_id')
            ->toArray();

        // Asignaturas válidas en ese grupo
        $asignaturasDelGrupo = AsignaturaGrado::where('grado_id', Grupo::findOrFail($grupoId)->grado_id)
            ->whereIn('asignatura_id', $asignaturasIdsDelProfesor)
            ->with('asignatura')
            ->get()
            ->pluck('asignatura', 'asignatura_id');

        return $asignaturasDelGrupo;
    }


        public function obtenerNotasFinales()
    {
        if ($this->grupoSeleccionado && $this->periodoSeleccionado) {
            $asignaturasAutorizadas = $this->obtenerAsignaturasDelProfesorEnGrupo($this->grupoSeleccionado);

            $notas = NotaFinal::with('asignatura', 'estudiante')
                ->where('colegio_id', $this->colegio->id)
                ->where('periodo_id', $this->periodoSeleccionado)
                ->where('grupo_id', $this->grupoSeleccionado)
                ->where('ano', now()->year)
                ->whereIn('asignatura_id', $asignaturasAutorizadas->keys())
                ->get();

            $this->notaFinalAsignaturas = $notas;
            $this->asignaturas = [];
            $estudiantes = [];
            $notasAgrupadas = []; // aquí guardaremos las notas por estudiante

            foreach ($notas as $notaFinal) {
                $estudianteId = $notaFinal->estudiante_id;
                $asignaturaId = $notaFinal->asignatura_id;
                $this->notasPorEstudiante["$estudianteId-$asignaturaId"] = $notaFinal->nota;

                $this->asignaturas[$asignaturaId] = $notaFinal->asignatura->nombre;
                $estudiantes[$estudianteId] = $notaFinal->estudiante->nombre_completo;

                // Agrupar las notas por estudiante
                $notasAgrupadas[$estudianteId][] = $notaFinal->nota;
            }

            // Calcular el promedio por estudiante
            $promedios = [];
            foreach ($notasAgrupadas as $estudianteId => $notasEstudiante) {
                $promedio = round(array_sum($notasEstudiante) / count($notasEstudiante), 2);
                $promedios[$estudianteId] = $promedio;
            }

            $this->estudiantes = $estudiantes;
            $this->promediosEstudiantes = $promedios; // <-- ahora puedes usar esta variable en tu vista
        }
    }



    public function actualizarNota($estudianteId, $asignaturaId)
    {
        $permitidas = asignaturaProfesor::where('profesor_id', $this->profesor->id)
        ->pluck('asignatura_id')
        ->toArray();

        if (!in_array($asignaturaId, $permitidas)) {
            abort(403, 'No tienes permiso para editar esta asignatura');
        }

        $clave = "$estudianteId-$asignaturaId";
        $nota = $this->notasPorEstudiante[$clave] ?? null;

        if (!is_null($nota)) {
            try {
                NotaFinal::updateOrCreate(
                    [
                        'estudiante_id' => $estudianteId,
                        'asignatura_id' => $asignaturaId,
                        'periodo_id' => $this->periodoSeleccionado,
                        'grupo_id' => $this->grupoSeleccionado,
                        'colegio_id' => $this->colegio->id,
                        'ano' => now()->year,
                    ],
                    ['nota' => $nota]
                );

                // notificacion
                $this->dispatch('alerta', [
                    'title' => 'Cambio de notas exitoso',
                    'text' => '¡Se guardó correctamente!',
                    'icon' => 'success',
                    'toast' => true,
                    'position' => 'top-end',
                ]);
            } catch (\Throwable $th) {
            // notificacion
            $this->dispatch('alerta', [
                'title' => 'Cambio de notas exitoso',
                'text' => $th->getMessage(),
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
            }
        }
    }



    public function updatedPeriodoSeleccionado($value)
    {
        if($value != null || $value != 0)
        {
            $this->obtenerNotasFinales();
        }
    }
    public function updatedGrupoSeleccionado($value)
    {
        if($value != null || $value != 0)
        {
            $this->asignaturas = AsignaturaGrado::where('grado_id',Grupo::findOrFail($value)->grado->id)->get();
            $this->obtenerNotasFinales();
        }
    }

    public function seleccionarGrupo($grupo)
    {
        $this->grupoSeleccionado = $grupo;
        $this->updatedGrupoSeleccionado($grupo);
    }

    public function seleccionarPeriodo($periodo)
    {
        $this->periodoSeleccionado = $periodo;
        $this->updatedPeriodoSeleccionado($periodo);
    }
    // datos basicos
    public $profesor;
    public $colegio;
    public $periodos = [];
    public $grupos = [];
    public function cargarPeriodos()
    {
        $this->periodos = PeriodoAcademico::where('colegio_id',$this->colegio->id)
        ->where('ano',now()->format('Y'))
        ->get();
    }
    public function cargarGrupos()
    {
        $asignaturasProfesor = asignaturaProfesor::where('profesor_id',$this->profesor->id)->get();
        $asignaturas = [];
        foreach($asignaturasProfesor as $asignaturaProfesor)
        {
            array_push($asignaturas,$asignaturaProfesor->asignatura);
        }
        $asignaturaGrados = [];
        foreach($asignaturas as $asignatura)
        {
            foreach($asignatura->asignaturaGrados as $asignaturaGrado)
            {
                array_push($asignaturaGrados,$asignaturaGrado);
            }
        }
        foreach($asignaturaGrados as $asignaturaGrado)
        {
            foreach($asignaturaGrado->grado->grupos as $grupo)
            {
                array_push($this->grupos,$grupo);
            }
        }
        // $this->grupos = Grupo::where('colegio_id',$this->colegio->id)->get();
    }
    public function mount()
    {
        $this->profesor = Profesor::where('user_id',Auth::user()->id)->first();
        $this->colegio = $this->profesor->colegio;
        $this->cargarPeriodos();
        $this->cargarGrupos();
    }
    public function render()
    {
        return view('livewire.docente.docente-notas-periodo');
    }
}
