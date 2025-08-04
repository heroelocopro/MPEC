<?php

namespace App\Livewire\Colegio;

use App\Models\Colegio;
use App\Models\configNota;
use App\Models\Estudiante;
use App\Models\Grado;
use App\Models\NotaFinal;
use App\Models\PeriodoAcademico;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ColegioHistorialAcademico extends Component
{
    // use
    use WithPagination;
    // datos iniciales
    public $colegio;
    public $usuario;
    // datos basicos
    public $grados;
    public $periodos;
    public $notaMinima;
    public $notasFinales = [];
    // selectores
    public $gradoSeleccionado;
    public Estudiante $estudianteSeleccionado;
    public $periodoSeleccionado;
    public $periodo_id;
    // modales
    public $mostrarModal = false;
    // filtros
    public $paginate = 5;
    public $search;
    public $gradoFiltro;
    public function updatedPeriodoId($value)
    {
         if($value != null)
         {
            $this->seleccionarPeriodo($value);
         }
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function mount()
    {
        $this->cargarUsuario();
        $this->cargarDatos();
    }
    public function seleccionarEstudiante($id)
    {
        $this->estudianteSeleccionado = Estudiante::with(['matricula.grado','notasFinales.asignatura','estudiantesGrupos.grupo'])->findOrFail($id);
        $this->cargarNotas();
        $this->mostrarModal = true;
    }
    public function seleccionarPeriodo($id)
    {
        $this->periodoSeleccionado = PeriodoAcademico::findOrFail($id);
        $this->cargarNotas();
    }
    public function cargarUsuario()
    {
        $this->usuario = Auth::user();
        $this->colegio = $this->usuario->role->nombre == "colegio" ? Colegio::where('user_id',$this->usuario->id)->first() : null;
    }
    public function cargarNotas()
    {
        $this->notasFinales = NotaFinal::with(['asignatura'])
        ->where('estudiante_id',$this->estudianteSeleccionado->id)
        ->where('periodo_id',$this->periodoSeleccionado->id)
        ->get();
    }
    public function cargarDatos()
    {
        $this->grados = Grado::where('colegio_id',$this->colegio->id)->get();
        $this->periodos = PeriodoAcademico::where('colegio_id',$this->colegio->id)->get();
        $this->periodoSeleccionado = $this->periodos != null && count($this->periodos)> 0 ? $this->periodos[0] : null;
        $this->notaMinima = configNota::where('colegio_id',$this->colegio->id)->first() ?? (object) ['nota_minima' => 3.5];
    }
        public function descargarNotas()
    {
        // cargar metodos
        if($this->periodoSeleccionado != null && $this->estudianteSeleccionado != null)
        {
            return redirect()->route('colegio-descargar-notas', ['periodo' => $this->periodoSeleccionado->id,'estudiante' => $this->estudianteSeleccionado->id]);
        }

    }
    public function render()
    {
        $estudiantes = Estudiante::with('matricula.grado')
        ->whereHas('matricula') // Asegura que tenga matrícula
        ->when($this->search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre_completo', 'like', "%$search%")
                ->orWhere('documento', 'like', "%$search%");
            });
        })
        ->when($this->gradoFiltro, function ($query, $gradoId) {
            $query->whereHas('matricula', function ($q) use ($gradoId) {
                $q->where('grado_id', $gradoId); // Aquí se filtra por el ID del grado
            });
        })
        ->where('colegio_id', $this->colegio->id)
        ->orderBy('created_at', 'desc')
        ->paginate($this->paginate);


        return view('livewire.colegio.colegio-historial-academico', compact('estudiantes'));
    }
}
