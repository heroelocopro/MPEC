<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevaActividadNotification extends Notification
{
    use Queueable;

    public function __construct(public $actividad) {}

    public function via($notifiable)
    {
        return ['database']; // También puedes agregar 'mail' si quieres correo
    }

    public function toDatabase($notifiable)
    {
        return [
            'titulo' => 'Nueva actividad: ' . $this->actividad->titulo,
            'descripcion' => $this->actividad->descripcion,
            'actividad_id' => $this->actividad->id,
            'docente' => $this->actividad->profesor->nombre_completo ?? 'Docente',
            'url' => route('estudiante-actividades'),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'mensaje' => 'Se ha publicado una nueva actividad: ' . $this->actividad->titulo,
            'actividad_id' => $this->actividad->id,
            'url' => route('estudiante-actividades'),
        ];
    }
}
