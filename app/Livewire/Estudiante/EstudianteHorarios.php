<?php

namespace App\Livewire\Estudiante;

use App\Models\Estudiante;
use App\Models\EstudianteGrupo;
use App\Models\Horario;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EstudianteHorarios extends Component
{
    // horario
    public $franjasHorarias = [
        '07:00 - 08:00' => [
            'lunes' => ['asignatura' => 'Matemáticas', 'docente' => 'Lic. Gómez', 'aula' => 'Aula 101'],
            'martes' => ['asignatura' => 'Lenguaje', 'docente' => 'Lic. Rivera', 'aula' => 'Aula 102'],
            'miercoles' => [],
            'jueves' => ['asignatura' => 'Historia', 'docente' => 'Lic. Duarte', 'aula' => 'Aula 104'],
            'viernes' => ['asignatura' => 'Inglés', 'docente' => 'Lic. Pérez', 'aula' => 'Aula 105'],
        ],
        '08:00 - 09:00' => [
            'lunes' => ['asignatura' => 'Ciencias', 'docente' => 'Lic. Salas', 'aula' => 'Aula 103'],
            'martes' => [],
            'miercoles' => ['asignatura' => 'Arte', 'docente' => 'Lic. Romero', 'aula' => 'Taller'],
            'jueves' => [],
            'viernes' => ['asignatura' => 'Educación Física', 'docente' => 'Lic. Torres', 'aula' => 'Cancha'],
        ],
        // más bloques horarios...
    ];
    public $horarios = [];
    public $diasConHorario = [];
    // datos basicos del colegio,estudiante
    public $colegio;
    public $estudiante;
    public $matricula;
    public $grado;
    public $grupo;

    public function cargarHorario()
    {
        $horarios = Horario::with(['grupo', 'asignatura', 'profesor']) // asegúrate de cargar estas relaciones
            ->where('grupo_id', $this->grupo->id)
            ->get();

        // Agrupar por hora_inicio, y dentro de eso por día
        $this->horarios = $horarios->groupBy('hora_inicio')->map(function ($bloquesPorHora) {
            return $bloquesPorHora->keyBy(fn($bloque) => strtolower($bloque->dia));
        });
        $this->diasConHorario = $horarios->pluck('dia')->map(fn($d) => strtolower($d))->unique()->values();
    }

    public function mount()
    {
        // datos basicos del estudiante
        $this->estudiante = Estudiante::where('user_id',Auth::user()->id)->first();
        $this->colegio = $this->estudiante->colegio ?? null;
        $this->matricula = $this->estudiante->matricula ?? null;
        $this->grado = $this->matricula->grado ?? null;
        $this->grupo = EstudianteGrupo::where('estudiante_id',$this->estudiante->id)->first()->grupo ?? null;
        if($this->grupo != null)
        {
            $this->cargarHorario();
        }
    }
    public function render()
    {
        return view('livewire.estudiante.estudiante-horarios');
    }
}
