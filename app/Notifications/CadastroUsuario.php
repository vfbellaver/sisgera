<?php

namespace Sisgera\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Sisgera\Models\User;

class CadastroUsuario extends Notification
{
    use Queueable;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        $route = route('invitation', ['token' => $this->user->invitation_token]);
        $appName = config('app.name');

        return (new MailMessage)
                    ->subject("Cadastro de usuário {$appName}!")
                    ->greeting("Olá {$this->user->name}!")
                    ->line("Você está pré cadastrado no  {$appName}!")
                    ->action('Cadastre sua senha', url('/'))
                    ->line('Obrigado!');
    }


    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
