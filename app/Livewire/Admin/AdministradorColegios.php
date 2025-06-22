<?php

namespace App\Livewire\Admin;

use App\Models\Colegio;
use App\Models\sedes_colegio;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdministradorColegios extends Component
{
    use WithPagination;
    protected $listeners = ['eliminarColegio' => 'eliminarColegio'];
    // colegio editar
    public $colegioEditar = [
        'nombre' => '',
        'codigo_dane' => '',
        'direccion' => '',
        'telefono' => '',
        'correo' => '',
        'departamento' => '',
        'municipio' => '',
        'estado' => '',
        'calendario' => '',
    ];
    public $modalEditar = false;
    public function editarColegio($id)
    {
        $colegio = Colegio::findOrFail($id);

        $this->colegioEditar = [
            'id' => $colegio->id, // importante para luego actualizar
            'nombre' => $colegio->nombre,
            'codigo_dane' => $colegio->codigo_dane,
            'direccion' => $colegio->direccion,
            'telefono' => $colegio->telefono,
            'correo' => $colegio->correo,
            'departamento' => $colegio->departamento,
            'municipio' => $colegio->municipio,
            'estado' => $colegio->estado,
            'calendario' => $colegio->calendario,
        ];

        $this->modalEditar = true;
    }

    public function actualizarColegio()
    {
        $this->validate([
            'colegioEditar.nombre' => 'required|string|max:255',
            'colegioEditar.codigo_dane' => 'required|string|max:20',
            'colegioEditar.direccion' => 'required|string|max:255',
            'colegioEditar.telefono' => 'nullable|string|max:20',
            'colegioEditar.correo' => 'nullable|email|max:255',
            'colegioEditar.departamento' => 'required|string',
            'colegioEditar.municipio' => 'required|string',
            'colegioEditar.estado' => 'required|in:ANTIGUO-INACTIVO,ANTIGUO-ACTIVO,NUEVO-ACTIVO,NUEVO-INACTIVO',
            'colegioEditar.calendario' => 'required|in:A,B',
        ]);

        $colegio = Colegio::findOrFail($this->colegioEditar['id']);
        $colegio->update($this->colegioEditar);

        $this->reset('modalEditar', 'colegioEditar');

        $this->dispatch('alerta', [
            'title' => 'Edición de colegio exitosa',
            'text' => '¡Se editó correctamente!',
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
        ]);
    }

    // colegio crear
    public $modalCrear = false;
    public array $colegio = [
        'nombre' => '',
        'codigo_dane' => '',
        'direccion' => '',
        'telefono' => '',
        'correo' => '',
        'departamento' => '',
        'municipio' => '',
        'estado' => '',
        'calendario' => '',
    ];
    // mostrar Sedes
    public $colegioSeleccionado;
    public $sedes = [];
    public $modalSedes = false;

    // Filtros y orden
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $pagination = 10;

    // Datos del usuario autenticado
    public $usuario;

    // Resetear a la primera página al filtrar
    protected $updatesQueryString = ['search', 'sortField', 'sortDirection', 'pagination'];
    protected $queryString = ['search' => ['except' => ''], 'pagination' => ['except' => 10]];

    public function eliminarColegio($id)
    {
        try {
            Colegio::findOrFail($id)->delete();
            $this->dispatch('alerta', [
                'title' => 'Eliminacion de colegio exitosa',
                'text' => '¡Se elimino correctamente!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Eliminacion de colegio fallida',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }

    public function guardarColegio()
{
    $this->validate([
        'colegio.nombre' => 'required|string|max:255',
        'colegio.codigo_dane' => 'required|string|max:20',
        'colegio.direccion' => 'required|string|max:255',
        'colegio.telefono' => 'nullable|string|max:20',
        'colegio.correo' => 'nullable|email|max:255',
        'colegio.departamento' => 'required|string',
        'colegio.municipio' => 'required|string',
        'colegio.estado' => 'required|string|in:Activo,Inactivo',
        'colegio.calendario' => 'required|string|in:A,B',
    ]);

    Colegio::create($this->colegio);
    $this->reset('modalCrear', 'colegio');
    $this->dispatch('alerta', [
        'title' => 'Creación de colegio exitosa',
        'text' => '¡Se creó correctamente!',
        'icon' => 'success',
        'toast' => true,
        'position' => 'top-end',
    ]);
}

    public function crearColegio()
    {
        $this->modalCrear = true;
    }

    public function mostrarSedes($colegioID)
    {
        $this->colegioSeleccionado = Colegio::findOrFail($colegioID);
        $this->sedes = $this->colegioSeleccionado->sedes;
        $this->modalSedes = true;
    }

    public function mount()
    {
        $this->usuario = Auth::user();
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Al escribir una búsqueda, vuelve a la página 1
    }

    public function updatingPagination()
    {
        $this->resetPage(); // Cambiar la cantidad por página también reinicia a página 1
    }

    public function sortBy($campo)
    {
        if ($this->sortField === $campo) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $campo;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $colegios = Colegio::where(function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('codigo_dane', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->pagination);

        return view('livewire.admin.administrador-colegios', compact('colegios'));
    }
}
