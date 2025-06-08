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
    // mostrar Sedes
    public $colegioSeleccionado;
    public $sedes = [];
    public $modalSedes = false;

    // Filtros y orden
    public $search = '';
    public $sortField = 'nombre';
    public $sortDirection = 'asc';
    public $pagination = 10;

    // Datos del usuario autenticado
    public $usuario;

    // Resetear a la primera página al filtrar
    protected $updatesQueryString = ['search', 'sortField', 'sortDirection', 'pagination'];
    protected $queryString = ['search' => ['except' => ''], 'pagination' => ['except' => 10]];

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
