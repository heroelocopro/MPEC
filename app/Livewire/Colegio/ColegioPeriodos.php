<?php

namespace App\Livewire\Colegio;

use App\Models\Colegio;
use App\Models\PeriodoAcademico;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ColegioPeriodos extends Component
{
    protected $listeners = ['eliminarPeriodoAcademico' => 'eliminarPeriodoAcademico', 'cambiarPeriodo' => 'cambiarPeriodo' ];
    public $modalCreacion = false;
    public $modalEdicion = false;
    public $modalCambio = true;
    public $colegio;
    public $periodos = [];
    // editar
    public $periodoSeleccionado;
    public $periodoEditarNombre;
    public $periodoEditarFechaInicio;
    public $periodoEditarFechaFin;
    // variables Periodo
    public $colegio_id;
    public $nombre;
    public $fecha_inicio;
    public $fecha_fin;
    public $estado;
    public $ano;

    public function eliminarPeriodoAcademico($id)
    {
        try {
            PeriodoAcademico::findOrFail($id)->delete();
            $this->dispatch('alerta', [
                'title' => 'Exito al eliminar periodo',
                'text' => 'se ha eliminado con exito!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Error al eliminar Periodo',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
        $this->cargarPeriodos();
    }
    public function actualizarPeriodoAcademico()
    {
        if($this->periodoSeleccionado != null)
        {
            $this->validate([
            'periodoEditarNombre' => 'required|string|max:100',
            'periodoEditarFechaInicio' => 'required|date',
            'periodoEditarFechaFin' => 'required|date|after_or_equal:periodoEditarFechaInicio',
            ]);
            $hoy = Carbon::today();
            if ($hoy->between($this->periodoEditarFechaInicio, $this->periodoEditarFechaFin)) {
                $this->estado = 'activo';
            } else {
                $this->estado = 'inactivo';
            }
            $this->ano =  now()->format('Y');
            $datos = [
                'nombre' => $this->periodoEditarNombre,
                'fecha_inicio' => $this->periodoEditarFechaInicio,
                'fecha_fin' => $this->periodoEditarFechaFin,
                'estado' => $this->estado,
                'ano' => now()->format('Y'),
                'colegio_id' => $this->colegio->id,
            ];
            try {
                PeriodoAcademico::findOrFail($this->periodoSeleccionado->id)->update($datos);
                $this->limpiar();
                $this->dispatch('alerta', [
                    'title' => 'Exito al actualizar periodo',
                    'text' => 'se ha actualizado con exito!',
                    'icon' => 'success',
                    'toast' => true,
                    'position' => 'top-end',
                ]);

            } catch (\Throwable $th) {
                $this->dispatch('alerta', [
                    'title' => 'Error al actualizar Periodo',
                    'text' => $th->getMessage(),
                    'icon' => 'error',
                    'toast' => true,
                    'position' => 'top-end',
                ]);
            }
            $this->cargarPeriodos();
        }
    }
    public function seleccionarPeriodo($id)
    {
        $this->periodoSeleccionado = PeriodoAcademico::findOrFail($id);
        $this->modalEdicion = true;
        $this->periodoEditarNombre = $this->periodoSeleccionado->nombre;
        $this->periodoEditarFechaInicio = Carbon::parse($this->periodoSeleccionado->fecha_inicio)->format('Y-m-d');
        $this->periodoEditarFechaFin = Carbon::parse($this->periodoSeleccionado->fecha_fin)->format('Y-m-d');
    }

    public function cambiarPeriodo($id)
    {
        try {
             PeriodoAcademico::activarPeriodo($id);
             $this->dispatch('alerta', [
                'title' => 'Exito al Activar periodo',
                'text' => 'se ha activado con exito!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
            $this->cargarPeriodos();
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Error al Cambiar Periodo',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }

    public function crearPeriodoAcademico()
    {
        $this->validate([
            'nombre' => 'required|string|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
        $hoy = Carbon::today();
            if ($hoy->between($this->fecha_inicio, $this->fecha_fin)) {
                $this->estado = 'activo';
            } else {
                $this->estado = 'inactivo';
            }
        $this->ano =  now()->format('Y');
        $datos = [
            'nombre' => $this->nombre,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'estado' => $this->estado,
            'ano' => $this->ano,
            'colegio_id' => $this->colegio_id,
        ];
        try {
            PeriodoAcademico::create($datos);
            $this->limpiar();
            $this->dispatch('alerta', [
                'title' => 'Exito al crear periodo',
                'text' => 'se ha creado con exito!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);

        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Error al crear Periodo',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
        $this->cargarPeriodos();
    }

    public function limpiar()
    {
        $this->nombre = '';
        $this->fecha_fin = '';
        $this->fecha_inicio = '';
        $this->estado = '';
        $this->ano = '';
        $this->periodoEditarNombre = '';
        $this->periodoEditarFechaInicio = '';
        $this->periodoEditarFechaFin = '';
        $this->modalCreacion = false;
        $this->modalEdicion = false;
    }

    public function cargarPeriodos()
    {
        $this->periodos = PeriodoAcademico::where('colegio_id',$this->colegio->id)->get();
    }
    public function mount()
    {
        $this->colegio = Colegio::where('user_id',Auth::user()->id)->first();
        $this->colegio_id = $this->colegio->id;
        $this->cargarPeriodos();
    }
    public function render()
    {
        return view('livewire.colegio.colegio-periodos');
    }
}
