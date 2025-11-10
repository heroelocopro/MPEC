<?php

namespace App\Livewire;

use App\Models\Foro;
use App\Models\Respuesta_Foro;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerForo extends Component
{
    // senales
    public $listeners = ['eliminar' => 'eliminar'];
    // datos iniciales
    public $usuario;
    // datos para crear
    public $autor_id,$tipo_autor,$mensaje;
    public $foro = null;
    public $comentarios;
    public $colegio;
    public function comentar()
    {
        $this->validate([
              'mensaje' => 'required|string|min:1|max:150'
        ]);
        $datos = [
            'foro_id' => $this->foro->id,
            'tipo_autor' => $this->usuario->role->nombre,
            'autor_id' => $this->usuario->id,
            'mensaje' => $this->mensaje,
        ];
        try {
            Respuesta_Foro::create($datos);
            $this->notificar('success','comentario creado','comentario creado con exito',true,'top-end');
            $this->reset(['mensaje','autor_id']);
        } catch (\Throwable $th) {
            $this->notificar('error','no se pudo crear el comentario',$th->getMessage(),false,'center');
        }
        $this->cargarComentarios();
    }
    public function eliminar($comentarioId)
    {
        try {
            $respuesta = Respuesta_Foro::findOrFail($comentarioId);
            $respuesta->delete();
             $this->notificar('success','comentario eliminado','comentario eliminado con exito',true,'top-end');
        } catch (\Throwable $th) {
             $this->notificar('error','Error al eliminar','comentario no se pudo eliminar con exito',true,'top-end');
        }
        $this->cargarComentarios();
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
    public function cargarComentarios()
    {
        $this->comentarios = $this->foro->respuestas;
    }
    public function cargarForo($id)
    {
        return Foro::find($id);
    }
    public function cargarDatos()
    {
        $this->colegio = $this->foro->colegio;
    }
    public function mount($id)
    {
        $this->usuario = Auth::user();
        $this->foro = $this->cargarForo($id);
        $this->cargarDatos();
        $this->cargarComentarios();
    }
    public function render()
    {
        return view('livewire.ver-foro');
    }
}
