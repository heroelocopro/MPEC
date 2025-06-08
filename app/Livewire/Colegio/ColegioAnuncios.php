<?php

namespace App\Livewire\Colegio;

use App\Models\Anuncio;
use App\Models\Colegio;
use App\Models\EstudianteGrupo;use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ColegioAnuncios extends Component
{
    protected $listeners = [
        'eliminarAnuncio' => 'eliminarAnuncio',
    ];
    use WithFileUploads;

    public $titulo = '';
    public $contenido = '';
    public $imagen;
    public $anuncios = [];
    public $anuncio = null;
    public $mostrarAnuncios = false;

    public function eliminarAnuncio($id)
    {
        try {
            $anuncio = Anuncio::findOrFail($id);
             // Borrar la imagen física si existe
            if ($anuncio->imagen && Storage::disk('public')->exists($anuncio->imagen)) {
                Storage::disk('public')->delete($anuncio->imagen);
            }

            // Eliminar el anuncio de la BD
             $anuncio->delete();
            $this->dispatch('alerta', [
                'title' => 'Eliminacion de anuncio',
                'text' => '¡Se elimino correctamente!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
            $this->cargarAnuncios();
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Eliminacion de anuncio',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }


    public function cargarAnuncios()
    {
        $this->anuncios = $this->colegio->anuncios()->latest()->get();
        $this->mostrarAnuncios = true;
    }

    public function limpiar()
    {
        $this->reset(['titulo', 'contenido', 'imagen']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function guardar()
    {
        $this->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ], [
            'titulo.required' => 'El título es obligatorio.',
            'contenido.required' => 'El contenido es obligatorio.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.max' => 'La imagen no debe ser mayor a 10MB.',
            'imagen.mimes' => 'Los formatos permitidos son jpeg, png, jpg y webp.',
        ]);

        try {
            $ruta = $this->imagen ? $this->imagen->store('anuncios', 'public') : null;

            $this->colegio->anuncios()->create([
                'titulo' => $this->titulo,
                'contenido' => $this->contenido,
                'imagen' => $ruta,
                'colegio_id' => $this->colegio->id,
            ]);

            // Limpiar campos y errores
            $this->limpiar();

            $this->dispatch('alerta', [
                'title' => 'Creación de anuncio',
                'text' => '¡Se creó correctamente!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);

            $this->cargarAnuncios();

        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Error al crear anuncio',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }

     // datos basicos del colegio,estudiante
     public $colegio;
     public function mount()
    {
        // datos basicos del estudiante
        $this->colegio = Colegio::where('user_id',Auth::user()->id)->first();
        // cargar metodos

    }
    public function render()
    {
        return view('livewire.colegio.colegio-anuncios');
    }
}
