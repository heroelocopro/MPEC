<?php

namespace App\Livewire\Colegio;

use App\Models\Colegio;
use App\Models\Grado;
use App\Models\Grupo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ColegioGrupos extends Component
{
    // edicion
    public $grupoEdicionId, $nombreEdicion, $gradoIdEdicion, $modalEdicion = false;
    // grupos

    public $nombre, $colegio_id, $grado_id, $modalCreacion = false;
    public function crearGrupo()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'grado_id' => 'required|exists:grados,id',
        ]);
        try {
            Grupo::create([
                'nombre' => $this->nombre,
                'grado_id' => $this->grado_id,
                'colegio_id' => $this->colegio_id,
            ]);
            $this->reset(['nombre', 'grado_id', 'modalCreacion']);
            $this->dispatch('alerta', [
                'title' => 'Grupo Creado!',
                'text' => '¡Se guardó correctamente!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Error!',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }
    public function editarGrupo($id)
    {
        $grupo = Grupo::findOrFail($id);
        $this->grupoEdicionId = $grupo->id;
        $this->nombreEdicion = $grupo->nombre;
        $this->gradoIdEdicion = $grupo->grado_id;
        $this->modalEdicion = true;
    }

    public function actualizarGrupo()
{
    $this->validate([
        'nombreEdicion' => 'required|string|max:255',
        'gradoIdEdicion' => 'required|exists:grados,id',
    ]);

    try {
        Grupo::findOrFail($this->grupoEdicionId)->update([
            'nombre' => $this->nombreEdicion,
            'grado_id' => $this->gradoIdEdicion,
        ]);

        $this->reset(['grupoEdicionId', 'nombreEdicion', 'gradoIdEdicion', 'modalEdicion']);
        $this->mount(); // para recargar los datos
        $this->dispatch('alerta', [
            'title' => 'Grupo Actualizado!',
            'text' => '¡Se actualizó correctamente!',
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
        ]);
    } catch (\Throwable $th) {
        $this->dispatch('alerta', [
            'title' => 'Error!',
            'text' => $th->getMessage(),
            'icon' => 'error',
            'toast' => true,
            'position' => 'top-end',
        ]);
    }
}


    public function eliminarGrupo($id)
    {
        try {
            Grupo::findOrFail($id)->delete();
            $this->dispatch('alerta', [
                'title' => 'Grupo Eliminado!',
                'text' => '¡Se elimino correctamente!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Error!',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }
    // datos basicos
    public $colegio;
    public $grupos;
    public $grados;
    public function mount()
    {
        $this->colegio = Colegio::where('user_id','=',Auth::user()->id)->first();
        $this->grupos = Grupo::where('colegio_id','=',$this->colegio->id)->get();
        $this->grados = Grado::where('colegio_id','=',$this->colegio->id)->get();
        //
        $this->colegio_id = $this->colegio->id;
    }
    public function render()
    {
        return view('livewire.colegio.colegio-grupos');
    }
}
