<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use App\Models\Estudiante;
use App\Models\EstudianteGrupo;
use App\Models\NotaFinal;
use App\Models\PeriodoAcademico;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstudianteController extends Controller
{
    public function mostrarInicio()
    {
        $usuario = Auth::user();
        $estudiante = Estudiante::where('user_id',$usuario->id)->first();
        $colegio = $estudiante->colegio;
        $anuncios = $colegio->anuncios()->latest()->get();
        return view('estudiante.inicio.index', compact('anuncios','estudiante'));
    }
    public function mostrarActividades()
    {
        return view('estudiante.actividades.index');
    }
    public function mostrarExamenes()
    {
        return view('estudiante.examenes.index');
    }
    public function mostrarHorarios()
    {
        return view('estudiante.horarios.index');
    }
    public function mostrarAsignaturas()
    {
        return view('estudiante.asignaturas.index');
    }
    public function mostrarDocentes()
    {
        return view('estudiante.docentes.index');
    }
    public function mostrarAnuncios()
    {
        return view('estudiante.anuncios.index');
    }
    public function descargar($periodo)
    {
        $estudiante = Estudiante::where('user_id', Auth::id())->firstOrFail();
        $grupo = EstudianteGrupo::where('estudiante_id', $estudiante->id)->first()->grupo;
        $colegio = $estudiante->colegio;
        $periodoObj = PeriodoAcademico::findOrFail($periodo);
        $notaMinima = 3.5;
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
