<?php

namespace App\Livewire;

use App\Models\Colegio;
use App\Models\Estudiante;
use App\Models\Foro as ModelsForo;
use App\Models\Grupo;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Foro extends Component
{
    use WithPagination;

    // datos iniciales
    public $usuario;
    public $colegio;
    public $profesor;
    public $estudiante;
    public $tipoUsuario;

    // datos necesarios
    public $grados = [];
    public $grupos = [];
    public $user_role = null;

    // datos para crear Foro
    public $titulo;
    public $contenido;
    public $tipo;
    public $grado_id;
    public $grupo_id;

    // filtros
    public $paginate = 5;
    public $filtroTipo = '';
    public $orden = 'desc';
    public $busqueda = '';

    // modales
    public $modalCrear = false;

    // Detectar cambio de filtros para resetear página
    public function updatingFiltroTipo() { $this->resetPage(); }
    public function updatingBusqueda() { $this->resetPage(); }
    public function updatingOrden() { $this->resetPage(); }
    public function updatingPaginate() { $this->resetPage(); }

    // Actualizar grupos al cambiar grado
    public function updatedGradoId($value)
    {
        if (!empty($value)) {
            $this->cargarGrupos();
        }
    }

    // Crear foro con validación
    public function crearForo()
    {
        $this->validate([
            'titulo' => 'string|required|min:1|max:100',
            'contenido' => 'required',
            'tipo' => 'required|in:Global,Grado,Grupo',
            'grado_id' => 'required_if:tipo,Grado,Grupo|nullable|exists:grados,id',
            'grupo_id' => 'required_if:tipo,Grupo|nullable|exists:grupos,id',
        ]);

        $datos = [
            'colegio_id' => $this->colegio->id,
            'titulo' => $this->titulo,
            'contenido' => $this->contenido,
            'autor_id' => $this->usuario->id,
            'tipo_autor' => $this->usuario->usuario->role->nombre,
            'tipo' => $this->tipo,
            'grado_id' => $this->grado_id,
            'grupo_id' => $this->grupo_id,
        ];

        try {
            ModelsForo::create($datos);
            $this->notificar('success', 'Foro Creado', 'Su foro fue creado.', false, 'center');
        } catch (\Throwable $th) {
            $this->notificar('error', 'No se pudo crear', $th->getMessage(), false, 'center');
        }

        $this->resetModal();
    }

    public function resetModal()
    {
        $this->modalCrear = false;
        $this->reset(['titulo', 'contenido', 'tipo', 'grado_id', 'grupo_id']);
    }

    public function detectarTipoUsuario()
    {
        $userId = Auth::id();

        if ($colegio = Colegio::where('user_id', $userId)->first()) {
            return $colegio;
        }

        if ($docente = Profesor::where('user_id', $userId)->first()) {
            return $docente;
        }

        if ($estudiante = Estudiante::where('user_id', $userId)->first()) {
            return $estudiante;
        }

        return null;
    }

    public function cargarGrupos()
    {
        $this->grupos = Grupo::where('grado_id', $this->grado_id)->get();

    }

    public function cargarDatos()
    {
        if ($this->usuario && $this->usuario->usuario->role_id == 2) {
            $this->colegio = $this->usuario;
            $this->tipoUsuario = "colegio";
        }

        if ($this->usuario && $this->usuario->usuario->role_id == 3) {
            $this->profesor = $this->usuario;
            $this->colegio = $this->profesor->colegio;
            $this->tipoUsuario = "docente";
        }

        if ($this->usuario && $this->usuario->usuario->role_id == 4) {
            $this->estudiante = $this->usuario;
            $this->colegio = $this->estudiante->colegio;
            $this->tipoUsuario = "estudiante";
        }

        if ($this->colegio) {
            $this->grados = $this->colegio->grados;
        }
    }

    private function notificar($type, $title, $text, $toast, $position)
    {
        $alerta = [
            'title' => $title,
            'text' => $text,
            'icon' => $type,
            'toast' => $toast,
            'position' => $position,
        ];

        $this->dispatch('alerta', $alerta);
    }

    public function mount()
    {
        $this->usuario = $this->detectarTipoUsuario();
        if($this->usuario != null)
        {
            $this->cargarDatos();
        }
    }

    public function render()
    {
        if($this->usuario == null)
        {
            $foros = [];
        }else{


        $foros = ModelsForo::where('colegio_id', $this->colegio->id)
            ->when($this->tipoUsuario === 'estudiante', function ($q) {
                $q->where(function ($q) {
                    $q->where('tipo', 'Global')
                    ->orWhere(function ($q) {
                        $q->where('tipo', 'Grado')
                            ->where('grado_id', $this->estudiante->matricula->grado->id);
                    })
                    ->orWhere(function ($q) {
                        $q->where('tipo', 'Grupo')
                            ->where('grupo_id', $this->estudiante->estudiantesGrupos->first()->grupo->id);
                    });
                });
            })
            // docentes y colegios no tienen filtro especial, ven todo

            ->when($this->filtroTipo, fn($q) => $q->where('tipo', $this->filtroTipo))
            ->when($this->busqueda, fn($q) => $q->where('titulo', 'like', '%' . $this->busqueda . '%'))
            ->orderBy('created_at', $this->orden)
            ->paginate($this->paginate);


        }




        return view('livewire.foro', [
            'foros' => $foros,
        ]);
    }
}
