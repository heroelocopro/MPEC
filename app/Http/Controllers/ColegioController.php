<?php

namespace App\Http\Controllers;

use App\Models\asignatura;
use App\Models\Colegio;
use App\Models\configNota;
use App\Models\Estudiante;
use App\Models\EstudianteGrupo;
use App\Models\Grupo;
use App\Models\matricula;
use App\Models\NotaFinal;
use App\Models\PeriodoAcademico;
use App\Models\Profesor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColegioController extends Controller
{
    public function mostrarInicio()
    {
        $colegio = Colegio::where('user_id', Auth::user()->id)->first() ?? (object) ['id' => null];
        $colegioId = $colegio->id;

        // Si no hay colegio, devolvemos datos vacíos
        if (!$colegioId) {
            return view('colegio.inicio.index', [
                'totalEstudiantes' => 0,
                'totalDocentes' => 0,
                'totalGrupos' => 0,
                'totalAsignaturas' => 0,
                'estudiantesPorGrupo' => [],
            ]);
        }

        $totalEstudiantes = matricula::where('colegio_id', $colegioId)->count();
        $totalDocentes = Profesor::where('colegio_id', $colegioId)->count();
        $totalGrupos = Grupo::where('colegio_id', $colegioId)->count();
        $totalAsignaturas = asignatura::where('colegio_id', $colegioId)->count();

        $estudiantesPorGrupo = Grupo::where('colegio_id', $colegioId)
            ->withCount('estudiantes')
            ->pluck('estudiantes_count', 'nombre')
            ->toArray();

        return view('colegio.inicio.index', compact(
            'totalEstudiantes',
            'totalDocentes',
            'totalGrupos',
            'totalAsignaturas',
            'estudiantesPorGrupo'
        ));

    }
    public function mostrarDocentes()
    {
        return view('colegio.docentes.index');
    }
    public function mostrarEstudiantes()
    {
        return view('colegio.estudiantes.index');
    }
    public function mostrarMatriculas()
    {
        return view('colegio.matriculas.index');
    }
    public function mostrarGrupos()
    {
        return view('colegio.grupos.index');
    }
    public function mostrarGrados()
    {
        return view('colegio.grados.index');
    }
    public function mostrarAsignaturas()
    {
        return view('colegio.asignaturas.index');
    }
    public function mostrarEstudiantesGrupos()
    {
        return view('colegio.estudiantes-grupos.index');
    }
    public function mostrarDocentesAsignaturas()
    {
         return view('colegio.docentes-asignaturas.index');
    }
    public function mostrarHorarios()
    {
        return view('colegio.horarios.index');
    }
    public function mostrarAsignaturasGrados()
    {
        return view('colegio.asignaturas-grados.index');
    }
    public function mostrarPeriodos()
    {
        return view('colegio.periodos.index');
    }

    public function mostrarAnuncios()
    {
        return view('colegio.anuncios.index');
    }
    public function descargarNotas($periodo,$estudiante)
    {
        $estudiante = Estudiante::with('colegio')->where('id',$estudiante)->first();
        $grupo = EstudianteGrupo::with('grupo')->where('estudiante_id', $estudiante->id)->first()->grupo;
        $colegio = $estudiante->colegio;
        $periodoObj = PeriodoAcademico::findOrFail($periodo);
        $notaMinima = configNota::where('colegio_id',$estudiante->colegio->id)->first()->nota_minima ?? 3.5;
        $plataforma = env('APP_NAME');
        $fecha = now();

        $notas = NotaFinal::with('asignatura')
            ->where('estudiante_id', $estudiante->id)
            ->where('periodo_id', $periodo)
            ->get()
            ->map(function ($nota) {
                return (object)[
                    'nombre' => $nota->asignatura->nombre,
                    'nota_final' => $nota->nota,
                ];
            });

        $pdf = Pdf::loadView('pdf.notas', [
            'estudiante' => $estudiante,
            'grupo' => $grupo,
            'colegio' => $colegio,
            'periodo' => $periodoObj,
            'notas' => $notas,
            'notaMinima' => $notaMinima,
            'plataforma' => $plataforma,
            'fecha' => $fecha,
        ]);

        $nombre = 'Notas_' . str_replace(' ', '_', $estudiante->nombre_completo).'_' . str_replace(' ', '_', $periodoObj->nombre) . '.pdf';

        return $pdf->download($nombre);
    }
}
