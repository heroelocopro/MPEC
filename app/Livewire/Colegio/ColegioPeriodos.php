<?php

namespace App\Livewire\Colegio;

use App\Models\Colegio;
use App\Models\PeriodoAcademico;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ColegioPeriodos extends Component
{
    protected $listeners = ['eliminarPeriodoAcademico' => 'eliminarPeriodoAcademico' ];
    public $modalCreacion = false;
    public $colegio;
    public $periodos = [];
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
    }


    public function crearPeriodoAcademico()
    {
        $this->validate([
            'nombre' => 'required|string|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
        $this->estado = 'activo';
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
    }

    public function limpiar()
    {
        $this->nombre = '';
        $this->fecha_fin = '';
        $this->fecha_inicio = '';
        $this->estado = '';
        $this->ano = '';
        $this->modalCreacion = false;
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
