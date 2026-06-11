<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'         => 'systeem',
            'title'        => 'Dit is een testmelding',
            'body'         => 'Als je dit ziet, werkt het meldingensysteem correct.',
            'url'          => 'https://www.google.com',
            'action_label' => 'googlz',
        ];
    }
}
