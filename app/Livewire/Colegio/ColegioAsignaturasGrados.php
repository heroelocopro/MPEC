<?php

namespace App\Livewire\Colegio;

use App\Models\Asignatura;
use App\Models\AsignaturaGrado;
use App\Models\Colegio;
use App\Models\Grado;
use App\Models\Grupo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ColegioAsignaturasGrados extends Component
{
    // Variables públicas
    public $grupo_id;
    public $grado_id;
    public $grado = null;
    public $modalCreacion = false;

    public $asignaturasGrados = [];
    public $asignaturasSeleccionadas = [];

    // Listeners
    protected $listeners = [
        'eliminarAsignacionAsignaturaGrado' => 'eliminarAsignacionAsignaturaGrado'
    ];

    // Reglas de validación
    protected function rules()
    {
        return [
            'grado_id' => 'required|integer|exists:grados,id',
            'asignaturasSeleccionadas' => 'required|array|min:1',
            'asignaturasSeleccionadas.*' => 'integer|exists:asignaturas,id',
        ];
    }

    /**
     * Elimina la relación asignatura-grado
     */
    public function eliminarAsignacionAsignaturaGrado($id)
    {
        try {
            AsignaturaGrado::findOrFail($id)->delete();
            $this->notificar('Asignatura-Grado', 'Desvinculación exitosa!', 'success');
        } catch (\Throwable $th) {
            $this->notificar('Asignatura-Grado', 'Desvinculación fallida: ' . $th->getMessage(), 'error');
        }

        $this->actualizarAsignaturasGrado();
    }

    /**
     * Asigna múltiples asignaturas al grado
     */
    public function asignarAsignaturaGrado()
    {
        $this->validate();

        try {
            foreach ($this->asignaturasSeleccionadas as $asignaturaId) {
                AsignaturaGrado::firstOrCreate([
                    'grado_id' => $this->grado_id,
                    'asignatura_id' => $asignaturaId,
                ]);
            }

            $this->notificar('Asignatura-Grado', 'Asignaciones exitosas!', 'success');
            $this->resetModal();
        } catch (\Throwable $th) {
            $this->notificar('Asignatura-Grado', 'Asignación fallida: ' . $th->getMessage(), 'error');
        }
    }

    /**
     * Resetea y actualiza el estado tras asignación
     */
    private function resetModal()
    {
        $this->modalCreacion = false;
        $this->asignaturasSeleccionadas = [];
        $this->actualizarAsignaturasGrado();
    }

    /**
     * Carga asignaturas asociadas al grado seleccionado
     */
    private function actualizarAsignaturasGrado()
    {
        $this->asignaturasGrados = AsignaturaGrado::where('grado_id', $this->grado_id)->get();
    }

    /**
     * Al cambiar el grado, se actualiza la información relacionada
     */
    public function updatedGradoId($value)
    {
        if (!empty($value)) {
            $this->grado = Grado::findOrFail($value);
            $this->actualizarAsignaturasGrado();
        }
    }

    /**
     * Enviar notificación al frontend
     */
    private function notificar($titulo, $mensaje, $icono)
    {
        $this->dispatch('alerta', [
            [
                'title' => $titulo,
                'text' => $mensaje,
                'icon' => $icono
            ]
        ]);
    }

    /**
     * Renderiza la vista
     */
    public function render()
    {
        $colegio = Colegio::where('user_id', Auth::id())->firstOrFail();

        $grupos = Grupo::where('colegio_id', $colegio->id)->get();
        $grados = Grado::where('colegio_id', $colegio->id)->get();

        $asignaturas = $this->grado
            ? Asignatura::whereNotIn('id', $this->grado->asignaturas->pluck('id'))->get()
            : [];

        return view('livewire.colegio.colegio-asignaturas-grados', compact('colegio', 'grupos', 'grados', 'asignaturas'));
    }
}
