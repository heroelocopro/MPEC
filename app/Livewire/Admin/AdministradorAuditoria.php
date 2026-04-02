<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
class AdministradorAuditoria extends Component
{
    use WithPagination;
    public $search = '';
    public $filtroEvento = '';
    public $modeloSeleccionado;
    public $periodos;
    public $sortField = 'audits.created_at';
    public $sortDirection = 'desc';
    public array $modelos;
    public int $paginate;


    public function mount()
    {
        $this->paginate = 10;
        
    }

    


    public function ordenar($campo)
{
    if ($this->sortField === $campo) {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        $this->sortField = $campo;
        $this->sortDirection = 'asc';
    }

    $this->resetPage(); // importante con paginación
}

    public function render()
    {
        $query = DB::table('audits')
            ->join('users', 'user_id', '=', 'users.id')
            ->select('audits.*', 'users.name');

        if ($this->modeloSeleccionado && $this->modeloSeleccionado !== "Todos") {
            $query->where('auditable_type', $this->modeloSeleccionado);
        }

        if ($this->filtroEvento && $this->filtroEvento !== "Todos") {
            $query->where('event', $this->filtroEvento);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('user_id', $this->search)
                ->orWhere('users.name', 'like', "%{$this->search}%")
                ->orWhere('users.email', 'like', "%{$this->search}%");
            });
        }
        $query->orderBy($this->sortField, $this->sortDirection);

        $auditorias = $query->latest()->paginate($this->paginate);

        return view('livewire.admin.administrador-auditoria', [
            'auditorias' => $auditorias
        ]);
    }
}
