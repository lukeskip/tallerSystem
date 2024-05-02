<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class ChoosePasswordNotification extends ResetPasswordNotification
{
    use Queueable;
    public $token;
    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $fromAddress = env('MAIL_FROM_ADDRESS', 'contacto@chekogarcia.com.mx');
        $fromName = env('MAIL_FROM_NAME', 'Sergio García');

        return (new MailMessage)
            ->from($fromAddress,  $fromName)
            ->subject('Ahora tienes accesso a Taller 1100')
            ->line('Recibes este correo electrónico porque se ha creado una cuenta para ti, ahora debes seleccionar una contraseña')
            ->action('Elegir Contraseña', url(config('app.url').route('password.reset', ['token' => $this->token], false)))->markdown('mails/default/mail/html/email');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
