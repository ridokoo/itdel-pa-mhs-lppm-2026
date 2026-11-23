<?php

namespace App\Notifications;

class SystemNotification
{
    public string $title;
    public string $message;
    public string $url;
    public string $icon;
    public ?int   $proposalId;
    public string $eventType;
    public array  $roles;

    public function __construct(
        string $title,
        string $message,
        string $url          = '#',
        string $icon         = 'ph-bell',
        ?int   $proposalId   = null,
        string $eventType    = 'general',
        array  $roles        = []
    ) {
        $this->title      = $title;
        $this->message    = $message;
        $this->url        = $url;
        $this->icon       = $icon;
        $this->proposalId = $proposalId;
        $this->eventType  = $eventType;
        $this->roles      = array_map('strtolower', $roles);
    }

    // Dipanggil oleh User::notify()
    public function toNotificationService($notifiable): array
    {
        return [
            'title'   => $this->title,
            'message' => $this->message,
            'options' => [
                'url'          => $this->url,
                'icon'         => $this->icon,
                'proposal_id'  => $this->proposalId,
                'event_type'   => $this->eventType,
                'target_roles' => $this->roles,
            ],
        ];
    }
}



