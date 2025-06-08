<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\matricula;
use App\Models\nota;
use App\Models\NotaFinal;
use App\Models\PeriodoAcademico;
use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocenteController extends Controller
{
    public function inicio()
    {
        return view('docente.inicio.inicio');
    }
    public function mostrarNotas()
    {
        return view('docente.notas.index');
    }
    public function mostrarAsistencias()
    {
        return view('docente.asistencias.index');
    }
    public function mostrarActividades()
    {
        return view('docente.actividades.index');
    }
    public function mostrarEvaluaciones()
    {
        return view('docente.evaluaciones.index');
    }

    public function notas()
    {
        $profesor = Profesor::where('user_id',Auth::user()->id)->first();
        $colegio = $profesor->colegio;
        $actividades = Actividad::where('profesor_id',$colegio->id)->get();
        $periodo = PeriodoAcademico::periodoActual($colegio->id);
        $notas = nota::all();
        $matriculas = matricula::where('colegio_id',$colegio->id)->get();
        $test = [];
        foreach ($matriculas as $matricula) {
            $estudiante = $matricula->estudiante;

            // Agrupar notas por asignatura
            $notasAgrupadas = $estudiante->notas->groupBy('asignatura_id');

            foreach ($notasAgrupadas as $asignaturaId => $notas) {
                $promedio = $notas->avg('valor'); // promedio solo de esta asignatura

                NotaFinal::updateOrCreate(
                    [
                        'ano' => now()->format('Y'),
                        'periodo_id' => $periodo->id,
                        'estudiante_id' => $estudiante->id,
                        'colegio_id' => $colegio->id,
                        'asignatura_id' => $asignaturaId,
                    ],
                    [
                        'nota' => $promedio,
                    ]
                );
            }
        }
        return view('docente.test', compact('test'));
    }
}
