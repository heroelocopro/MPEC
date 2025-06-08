<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevoExamenNotification extends Notification
{
    use Queueable;

    public function __construct(public $examen) {}

    public function via($notifiable)
    {
        return ['database']; // Puedes añadir 'mail' si también quieres enviar por correo
    }

    public function toDatabase($notifiable)
    {
        return [
            'titulo' => 'Nuevo examen: ' . $this->examen->titulo,
            'descripcion' => $this->examen->descripcion ?? '',
            'examen_id' => $this->examen->id,
            'docente' => $this->examen->profesor->nombre_completo ?? 'Docente',
            'url' => route('estudiante-examenes'), // Ajusta esta ruta a la tuya
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
