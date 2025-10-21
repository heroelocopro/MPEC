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

    protected $updatesQueryString = ['search', 'sortField', 'sortDirection', 'pagination'];
    protected $queryString = ['search' => ['except' => ''], 'pagination' => ['except' => 10]];

    // =====================================================
    // 🔹 Funciones de normalización de datos
    // =====================================================

    private function normalizar($data)
    {
        return array_map(function ($value) {
            if (is_string($value)) {
                $valor = trim($value);
                // Remueve dobles espacios, tildes o caracteres extraños
                $valor = preg_replace('/\s+/', ' ', $valor);
                // Convierte entidades especiales y quita espacios
                return ucfirst(mb_strtolower($valor, 'UTF-8'));
            }
            return $value;
        }, $data);
    }

    private function normalizarMayus($data)
    {
        return array_map(function ($value) {
            if (is_string($value)) {
                return strtoupper(trim(preg_replace('/\s+/', ' ', $value)));
            }
            return $value;
        }, $data);
    }

    // =====================================================
    // 🔹 Funciones de edición
    // =====================================================

    public function editarColegio($id)
    {
        $colegio = Colegio::findOrFail($id);

        $this->colegioEditar = [
            'id' => $colegio->id,
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
            'colegioEditar.telefono' => 'nullable|string|max:40',
            'colegioEditar.correo' => 'nullable|email|max:255',
            'colegioEditar.departamento' => 'required|string',
            'colegioEditar.municipio' => 'required|string',
            'colegioEditar.estado' => 'required|in:ANTIGUO-INACTIVO,ANTIGUO-ACTIVO,NUEVO-ACTIVO,NUEVO-INACTIVO',
            'colegioEditar.calendario' => 'required|in:A,B',
        ], $this->messages());
         $estadoSinNormalizar = $this->colegioEditar['estado'];
        $dataNormalizada = $this->normalizarMayus($this->colegioEditar);
        $dataNormalizada['estado'] = $estadoSinNormalizar;

        $colegio = Colegio::findOrFail($this->colegioEditar['id']);
        $colegio->update($dataNormalizada);

        $this->reset('modalEditar', 'colegioEditar');

        $this->dispatch('alerta', [
            'title' => 'Edición de colegio exitosa',
            'text' => '¡Se editó correctamente!',
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
        ]);
    }

    // =====================================================
    // 🔹 Funciones de creación
    // =====================================================

    public function crearColegio()
    {
        $this->modalCrear = true;
    }

    public function guardarColegio()
    {
        $this->validate([
            'colegio.nombre' => 'required|string|max:255',
            'colegio.codigo_dane' => 'required|string|max:20',
            'colegio.direccion' => 'required|string|max:255',
            'colegio.telefono' => 'nullable|string|max:40',
            'colegio.correo' => 'nullable|email|max:255',
            'colegio.departamento' => 'required|string',
            'colegio.municipio' => 'required|string',
            'colegio.estado' => 'required|in:ANTIGUO-INACTIVO,ANTIGUO-ACTIVO,NUEVO-ACTIVO,NUEVO-INACTIVO',
            'colegio.calendario' => 'required|string|in:A,B',
        ], $this->messages());

        $estadoSinNormalizar = $this->colegio['estado'];
        $dataNormalizada = $this->normalizarMayus($this->colegio);
        $dataNormalizada['estado'] = $estadoSinNormalizar;

        Colegio::create($dataNormalizada);

        $this->reset('modalCrear', 'colegio');

        $this->dispatch('alerta', [
            'title' => 'Creación de colegio exitosa',
            'text' => '¡Se creó correctamente!',
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
        ]);
    }

    // =====================================================
    // 🔹 Eliminar colegio
    // =====================================================

    public function eliminarColegio($id)
    {
        try {
            Colegio::findOrFail($id)->delete();
            $this->reset();
            $this->dispatch('alerta', [
                'title' => 'Eliminación de colegio exitosa',
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

    // =====================================================
    // 🔹 Mostrar sedes
    // =====================================================

    public function mostrarSedes($colegioID)
    {
        $this->colegioSeleccionado = Colegio::findOrFail($colegioID);
        $this->sedes = $this->colegioSeleccionado->sedes;
        $this->modalSedes = true;
    }

    // =====================================================
    // 🔹 Funciones del componente
    // =====================================================

    public function mount()
    {
        $this->usuario = Auth::user();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPagination()
    {
        $this->resetPage();
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

    // =====================================================
    // 🔹 Mensajes personalizados de validación
    // =====================================================

    protected function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser texto.',
            'email' => 'El campo :attribute debe ser un correo válido.',
            'max' => 'El campo :attribute no debe exceder :max caracteres.',
            'in' => 'El campo :attribute contiene un valor inválido.',
        ];
    }
}
