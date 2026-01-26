<?php

namespace App\Livewire\Docente;

use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocenteAnuncios extends Component
{
    use WithFileUploads;

    // ====== Variables públicas (inputs del formulario) ======
    public $titulo = '';
    public $contenido = '';
    public $imagen = null;
    public $colegio_id = null;
    public $anunciable_id = null;
    public $anunciable_type = 'App\Models\Profesor';

    // ====== Relaciones ======
    public $profesor;
    public $colegio;

    // ====== Reglas de validación ======
    protected $rules = [
        'titulo' => 'required|string|max:255',
        'contenido' => 'required|string',
        'imagen' => 'nullable|image|max:2048', // máx. 2MB
        'colegio_id' => 'required|exists:colegios,id',
        'anunciable_id' => 'required|integer|exists:profesores,id',
        'anunciable_type' => 'required|string|in:App\Models\Profesor',
    ];

    /**
     * Limpia los campos del formulario
     */
    public function limpiar(): void
    {
        $this->reset(['titulo', 'contenido', 'imagen']);
    }

    /**
     * Crea un nuevo anuncio del profesor
     */
    public function crearAnuncio(): void
    {
        // Validar antes de crear
        $this->validate();

        try {
            // Subir imagen si existe
            $imagenPath = $this->imagen
                ? $this->imagen->store('docente/anuncios', 's3', ['visibility' => 'public']) : null;
                

            // Verificar relaciones
            if (!$this->profesor || !$this->profesor->exists) {
                throw new \Exception('No se encontró el profesor autenticado.');
            }

            if (!$this->colegio || !$this->colegio->exists) {
                throw new \Exception('El profesor no tiene colegio asignado.');
            }

            // Crear el anuncio
            $this->profesor->anuncios()->create([
                'titulo' => $this->titulo,
                'contenido' => $this->contenido,
                'imagen' => $imagenPath,
                'colegio_id' => $this->colegio_id,
            ]);

            // Limpiar formulario
            $this->limpiar();

            // Emitir alerta de éxito
            $this->dispatch('alerta', [
                'title' => 'Anuncio publicado',
                'text' => '¡El anuncio fue creado correctamente!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            // Capturar cualquier error (de validación o base de datos)
            $this->dispatch('alerta', [
                'title' => 'Error al guardar el anuncio',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }

    /**
     * Cargar datos iniciales del profesor
     */
    public function mount(): void
    {
        $this->profesor = Profesor::where('user_id', Auth::id())->first();

        if (!$this->profesor) {
            // Evitar errores si el usuario no es profesor
            $this->colegio = (object)['id' => null];
            $this->colegio_id = null;
            $this->anunciable_id = null;
            return;
        }

        $this->colegio = $this->profesor->colegio ?? (object)['id' => null];
        $this->colegio_id = $this->colegio->id ?? null;
        $this->anunciable_id = $this->profesor->id;
    }

    /**
     * Renderiza la vista del componente
     */
    public function render()
    {
        return view('livewire.docente.docente-anuncios');
    }
}
