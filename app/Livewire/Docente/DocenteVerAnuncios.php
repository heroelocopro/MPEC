<?php

namespace App\Livewire\Docente;

use App\Models\Anuncio;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocenteVerAnuncios extends Component
{
    // editar
    use WithFileUploads;
    protected $rules = [
        'titulo' => 'required|string|max:255',
        'contenido' => 'required|string',
        'imagenNueva' => 'nullable|image|max:2048', // max 2MB
    ];
    public $editarModal = false;
    public $anuncio;
    public $titulo;
    public $contenido;
    public $imagenVieja;
    public $imagenNueva;
    // cargamos el anuncio a la variable y dividimos lo demas
    public function cargarAnuncio($id)
    {
        $this->anuncio = Anuncio::findOrFail($id);
        $this->titulo = $this->anuncio->titulo;
        $this->contenido = $this->anuncio->contenido;
        $this->imagenVieja = $this->anuncio->imagen;
        $this->editarModal = true;
    }
    public function limpiarEdicion()
    {
        $this->editarModal = false;
    }
    public function editar()
    {
        // validamos con la regla de arriba
        $this->validate($this->rules);
        try {
            $imagePath = $this->imagenVieja;
            // validamos si van a cambiar la imagen
            if($this->imagenNueva)
            {
                if ($this->imagenVieja && Storage::disk('public')->exists($this->imagenVieja)) {
                    Storage::disk('public')->delete($this->imagenVieja);
                }
                $imagePath = $this->imagenNueva->store('anuncios','public');
            }
            $datos = [
                'titulo' => $this->titulo,
                'contenido' => $this->contenido,
                'imagen' => $imagePath,
            ];
            // actualizamos
            $this->anuncio->update($datos);
            // cargamos otra vez
            $this->limpiarEdicion();
            $this->cargarAnuncios();
            // alerta
            $this->dispatch('alerta', [
                'title' => 'Anuncio Editado',
                'text' => '¡Se ha Editado correctamente!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Error al editar anuncio',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }
    // eliminar
    protected $listeners = ['eliminarAnuncio' => 'eliminarAnuncio'];
    public function eliminarAnuncio($id)
    {
        try {
            // obtenemos el anuncio
            $anuncio = Anuncio::findOrFail($id);
            // borramos la foto guardada si existe
            // Borramos la imagen si existe
            if ($anuncio->imagen && Storage::disk('public')->exists($anuncio->imagen)) {
                Storage::disk('public')->delete($anuncio->imagen);
            }
            // borramos
            $anuncio->delete();
            $this->cargarAnuncios();
            $this->dispatch('alerta', [
                'title' => 'Anuncio eliminado',
                'text' => '¡Se elimino correctamente!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Anuncio error',
                'text' => $th->getMessage(),
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }
    // datos para cargar
    public $anuncios = [];
    public function cargarAnuncios()
    {
        $this->anuncios = $this->profesor->anuncios()->latest()->get();
    }
    // datos basicos para servir
    public $profesor;
    public $colegio;
    public function mount()
    {
        $this->profesor = Profesor::where('user_id',Auth::user()->id)->first();
        $this->colegio = $this->profesor->colegio;
        $this->cargarAnuncios();
    }
    public function render()
    {
        return view('livewire.docente.docente-ver-anuncios');
    }
}
