<?php

namespace App\Http\Controllers;

use App\Models\asignatura;
use App\Models\Colegio;
use App\Models\EstudianteGrupo;
use App\Models\Grupo;
use App\Models\matricula;
use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColegioController extends Controller
{
    public function mostrarInicio()
    {
        $colegioId = Colegio::where('user_id',Auth::user()->id)->first()->id;
        $totalEstudiantes = count(matricula::where('colegio_id',$colegioId)->get());
        $totalDocentes = count(Profesor::where('colegio_id',$colegioId)->get());
        $totalGrupos = count(Grupo::where('colegio_id',$colegioId)->get());
        $totalAsignaturas = count(asignatura::where('colegio_id',$colegioId)->get());
        $estudiantesPorGrupo = Grupo::withCount('estudiantes')->pluck('estudiantes_count', 'nombre')->toArray();
        return view('colegio.inicio.index', compact('totalEstudiantes','totalDocentes','totalGrupos','totalAsignaturas','estudiantesPorGrupo'));
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
}
