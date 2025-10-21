<?php

namespace App\Livewire\Colegio;

use App\Models\Colegio;
use App\Models\Grado;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ColegioGrados extends Component
{
    public $modalCreacion = false;
    public $modalEdicion = false;

    public $nombre, $nivel, $descripcion, $edad_referencia, $estado = true;
    public $grado_id, $nombreEdicion, $nivelEdicion, $descripcionEdicion, $edad_referenciaEdicion, $estadoEdicion;

    protected $listeners = [
        'eliminarGrado' => 'eliminarGrado',
    ];

    protected $rules = [
        'nombre' => 'required|string|min:3|max:100',
        'nivel' => 'required|in:preescolar,primaria,secundaria,media',
        'descripcion' => 'nullable|string|max:500',
        'edad_referencia' => 'nullable|string|max:20',
        'estado' => 'boolean'
    ];

    protected $messages = [
        'nombre.required' => 'El nombre del grado es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
        'nivel.required' => 'Debe seleccionar un nivel.',
        'nivel.in' => 'El nivel seleccionado no es válido.',
    ];

    public function render()
    {
        $colegio = Colegio::where('user_id', Auth::id())->first();
        $grados = Grado::where('colegio_id', $colegio->id)->orderBy('nombre')->get();
        return view('livewire.colegio.colegio-grados', compact('grados', 'colegio'));
    }

    public function crearGrado()
    {
        $this->validate();

        $colegio = Colegio::where('user_id', Auth::id())->first();
        if (!$colegio) {
            $this->dispatch('alerta', [
                'title' => 'Error',
                'text' => 'No se encontró el colegio asociado al usuario.',
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
            return;
        }

        $nombreNormalizado = ucfirst(strtolower(trim($this->nombre)));

        Grado::create([
            'nombre' => $nombreNormalizado,
            'nivel' => strtolower($this->nivel),
            'descripcion' => ucfirst(trim($this->descripcion)),
            'edad_referencia' => trim($this->edad_referencia),
            'estado' => $this->estado,
            'colegio_id' => $colegio->id,
        ]);

        $this->reset(['nombre', 'nivel', 'descripcion', 'edad_referencia', 'estado', 'modalCreacion']);
        $this->dispatch('alerta', [
            'title' => 'Grado Creado!',
            'text' => '¡Se creó correctamente!',
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
        ]);
    }

    public function editarGrado($id)
    {
        $grado = Grado::find($id);
        if (!$grado) {
            $this->dispatch('alerta', [
                'title' => 'Error!',
                'text' => '¡No se encontró el grado!',
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
            return;
        }

        $this->grado_id = $grado->id;
        $this->nombreEdicion = $grado->nombre;
        $this->nivelEdicion = $grado->nivel;
        $this->descripcionEdicion = $grado->descripcion;
        $this->edad_referenciaEdicion = $grado->edad_referencia;
        $this->estadoEdicion = $grado->estado;
        $this->modalEdicion = true;
    }

    public function actualizarGrado()
    {
        $this->validate([
            'nombreEdicion' => 'required|string|min:3|max:100',
            'nivelEdicion' => 'required|in:preescolar,primaria,secundaria,media',
        ]);

        $grado = Grado::find($this->grado_id);
        if (!$grado) {
            $this->dispatch('alerta', [
                'title' => 'Error!',
                'text' => 'No se encontró el grado a actualizar.',
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
            return;
        }

        $grado->update([
            'nombre' => ucfirst(strtolower(trim($this->nombreEdicion))),
            'nivel' => strtolower($this->nivelEdicion),
            'descripcion' => ucfirst(trim($this->descripcionEdicion)),
            'edad_referencia' => trim($this->edad_referenciaEdicion),
            'estado' => (bool)$this->estadoEdicion,
        ]);

        $this->reset(['modalEdicion', 'grado_id']);
        $this->dispatch('alerta', [
            'title' => 'Grado Actualizado!',
            'text' => '¡Los cambios se guardaron correctamente!',
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
        ]);
    }

    public function eliminarGrado($id)
    {
        $grado = Grado::find($id);

        if (!$grado) {
            $this->dispatch('alerta', [
                'title' => 'Error!',
                'text' => '¡No se encontró el grado!',
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
            return;
        }

        $grado->delete();
        $this->dispatch('alerta', [
            'title' => 'Grado Eliminado!',
            'text' => '¡Se eliminó correctamente!',
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
        ]);
    }
}
