<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notificaciones extends Component
{
    public $open = false;
    public function marcarComoLeida($id)
    {
        $notificacion = Auth::user()->notifications()->find($id);
        if ($notificacion) {
            $notificacion->markAsRead();
        }
    }

    public function marcarTodasComoLeidas()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        return view('livewire.notificaciones', [
            'notificaciones' => Auth::user()->notifications()->latest()->take(10)->get(),
            'noLeidas' => Auth::user()->unreadNotifications()->count(),
        ]);
    }
}
