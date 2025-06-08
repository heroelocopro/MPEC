<?php

namespace App\Livewire\Docente;

use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocenteAnuncios extends Component
{
    use WithFileUploads;
    public $titulo, $contenido, $imagen, $colegio_id, $anunciable_id, $anunciable_type = 'App\Models\Profesor';
    public $profesor;
    public $colegio;
    protected $rules = [
        'titulo' => 'required|string|max:255',
        'contenido' => 'required|string',
        'imagen' => 'nullable|image|max:2048', // max 2MB
        'colegio_id' => 'required|exists:colegios,id',
        'anunciable_id' => 'required|integer',
        'anunciable_type' => 'required|string|in:App\Models\Profesor',
    ];

    public function limpiar()
    {
        $this->titulo = '';
        $this->contenido = '';
        $this->imagen = null;
    }

    public function crearAnuncio()
    {
        $this->validate($this->rules);
        try {
            // validamos si hay imagen
            if($this->imagen)
            {
                $imagenPath = $this->imagen->store('anuncios','public');
            }else{
                $imagenPath = null;
            }
            // creamos
            $this->profesor->anuncios()->create([
                'titulo' => $this->titulo,
                'contenido' => $this->contenido,
                'imagen' => $imagenPath,
                'colegio_id' => $this->colegio_id,
            ]);
            // limpiamos
            $this->limpiar();
            // llamamos para los anuncios

            // llamamos la alerta.
            $this->dispatch('alerta', [
                'title' => 'Anuncio exitoso',
                'text' => '¡Se guardó correctamente!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Error al guardar',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }
    public function mount()
    {
        $this->profesor = Profesor::where('user_id',Auth::user()->id)->first();
        $this->colegio = $this->profesor->colegio;
        $this->colegio_id = $this->colegio->id;
        $this->anunciable_id = $this->profesor->id;
    }
    public function render()
    {
        return view('livewire.docente.docente-anuncios');
    }
}
