<?php

namespace App\Notifications;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Reservation $reservation)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = match ($this->reservation->status) {
            'pending' => 'Solicitud recibida, pendiente de confirmación',
            'confirmed' => 'Reserva confirmada',
            'completed' => 'Reserva completada',
            'cancelled' => 'Reserva cancelada',
            'no_show' => 'Reserva marcada como inasistencia',
            default => 'Estado actualizado',
        };

        return (new MailMessage)
            ->subject("Reserva #RSV-{$this->reservation->id}: {$status}")
            ->greeting("Hola {$this->reservation->nombre}")
            ->line($status . '.')
            ->line('Fecha: ' . Carbon::parse($this->reservation->fecha)->format('d/m/Y') . ' a las ' . $this->reservation->hora)
            ->line('Espacio: ' . ($this->reservation->table?->name ?? $this->reservation->zona ?? $this->reservation->mesa_id ?? 'Por asignar'))
            ->line('Personas: ' . $this->reservation->personas)
            ->line('Código de reserva: RSV-' . str_pad((string) $this->reservation->id, 4, '0', STR_PAD_LEFT));
    }
}