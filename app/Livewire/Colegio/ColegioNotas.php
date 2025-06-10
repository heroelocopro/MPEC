<?php

namespace App\Livewire\Colegio;

use App\Models\Colegio;
use App\Models\configNota;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ColegioNotas extends Component
{
    public function guardarConfiguracion()
    {
        // Aquí puedes guardar los valores en una tabla de configuración del colegio
        $this->validate([
            'nota_minima' => 'required|numeric|min:0|max:100',
            'nota_maxima' => 'required|numeric|min:0|max:100',
            'nota_requerida' => 'required|numeric|min:0|max:100|gte:nota_minima|lte:nota_maxima',
        ]);
        try {
            $datos = [
                'nota_minima' => $this->nota_minima,
                'nota_maxima' => $this->nota_maxima,
                'nota_requerida' => $this->nota_requerida,
                'colegio_id' => $this->colegio->id,
            ];
            configNota::create($datos);
            $this->dispatch('alerta', [
                'title' => 'Actualización exitosa',
                'text' => 'La configuracion fue actualizada correctamente',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Actualización Fallida',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }
    // configuraicon notas
    public $nota_minima = 0;
    public $nota_maxima;
    public $nota_requerida;
    // valores basicos
    public $colegio;
    public function mount()
    {
        $this->colegio = Colegio::where('user_id', Auth::user()->id)->first();

        $configNota = configNota::where('colegio_id', $this->colegio->id)->first();

        // Si no existe configNota, asigna valores por defecto
        $this->nota_minima = optional($configNota)->nota_minima ?? 0;
        $this->nota_maxima = optional($configNota)->nota_maxima ?? 0;
        $this->nota_requerida = optional($configNota)->nota_requerida ?? 0;
    }

    public function render()
    {
        return view('livewire.colegio.colegio-notas');
    }
}
