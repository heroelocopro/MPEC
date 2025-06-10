<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevoAnuncioNotification extends Notification
{
    use Queueable;

    public function __construct(public $anuncio) {}

    public function via($notifiable)
    {
        return ['database']; // Puedes añadir 'mail' si deseas enviar correos también
    }

    public function toDatabase($notifiable)
    {
        return [
            'titulo' => 'Nuevo anuncio: ' . $this->anuncio->titulo,
            'descripcion' => $this->anuncio->contenido ?? '',
            'anuncio_id' => $this->anuncio->id,
            'autor' => $this->anuncio->autor->nombre_completo ?? 'Administrador',
            'url' => route('estudiante.anuncios.ver', $this->anuncio->id), // Ajusta el nombre de ruta según tu sistema
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
