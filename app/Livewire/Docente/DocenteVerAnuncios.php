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
    use WithFileUploads;

    // ====== Propiedades del componente ======
    public $editarModal = false;
    public $anuncio;
    public $titulo;
    public $contenido;
    public $imagenVieja;
    public $imagenNueva;
    public $anuncios = [];
    public $profesor;
    public $colegio;

    // ====== Validaciones ======
    protected $rules = [
        'titulo' => 'required|string|max:255',
        'contenido' => 'required|string',
        'imagenNueva' => 'nullable|image|max:2048', // máx. 2 MB
    ];

    // Escucha de eventos Livewire (desde JS)
    protected $listeners = ['eliminarAnuncio' => 'eliminarAnuncio'];

    // ============================
    // === Métodos principales ===
    // ============================

    /**
     * Carga el anuncio seleccionado en los campos para edición.
     */
    public function cargarAnuncio(int $id): void
    {
        $this->anuncio = Anuncio::findOrFail($id);
        $this->titulo = $this->anuncio->titulo;
        $this->contenido = $this->anuncio->contenido;
        $this->imagenVieja = $this->anuncio->imagen;
        $this->editarModal = true;
    }

    /**
     * Limpia y cierra el modal de edición.
     */
    public function limpiarEdicion(): void
    {
        $this->reset(['editarModal', 'anuncio', 'titulo', 'contenido', 'imagenVieja', 'imagenNueva']);
    }

    /**
     * Edita el anuncio actual con los datos actualizados.
     */
    public function editar(): void
    {
        $this->validate();

        try {
            // Ruta por defecto
            $imagePath = $this->imagenVieja;

            // Si hay nueva imagen, borrar la anterior y guardar la nueva
            if ($this->imagenNueva) {
                if ($this->imagenVieja && Storage::disk('public')->exists($this->imagenVieja)) {
                    Storage::disk('public')->delete($this->imagenVieja);
                }

                $imagePath = $this->imagenNueva->store('anuncios', 'public');
            }

            // Actualizar los datos del anuncio
            $this->anuncio->update([
                'titulo' => $this->titulo,
                'contenido' => $this->contenido,
                'imagen' => $imagePath,
            ]);

            // Recargar listado
            $this->limpiarEdicion();
            $this->cargarAnuncios();

            // Éxito
            $this->dispatch('alerta', [
                'title' => 'Anuncio editado',
                'text' => '¡Se ha editado correctamente!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            // Error controlado
            $this->dispatch('alerta', [
                'title' => 'Error al editar anuncio',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }

    /**
     * Elimina un anuncio (imagen incluida si existe).
     */
    public function eliminarAnuncio(int $id): void
    {
        try {
            $anuncio = Anuncio::findOrFail($id);

            if ($anuncio->imagen && Storage::disk('public')->exists($anuncio->imagen)) {
                Storage::disk('public')->delete($anuncio->imagen);
            }

            $anuncio->delete();

            $this->cargarAnuncios();

            $this->dispatch('alerta', [
                'title' => 'Anuncio eliminado',
                'text' => '¡Se eliminó correctamente!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Error al eliminar',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }

    /**
     * Carga los anuncios del profesor autenticado.
     */
    public function cargarAnuncios(): void
    {
        if ($this->profesor && $this->profesor->exists) {
            $this->anuncios = $this->profesor->anuncios()->latest()->get();
        } else {
            $this->anuncios = collect();
        }
    }

    // ============================
    // === Ciclo de vida Livewire ===
    // ============================

    public function mount(): void
    {
        $this->profesor = Profesor::where('user_id', Auth::id())->first();

        if (!$this->profesor) {
            $this->colegio = null;
            $this->anuncios = collect();
            return;
        }

        $this->colegio = $this->profesor->colegio ?? null;
        $this->cargarAnuncios();
    }

    public function render()
    {
        return view('livewire.docente.docente-ver-anuncios');
    }
}
