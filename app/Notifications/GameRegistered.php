<?php

namespace App\Notifications;

use App\Former\Game;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Auth\Authenticatable;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;

class GameRegistered extends Notification
{
    use Queueable;

    public Game $game;
    public Authenticatable $user;

    /**
     * Create a new notification instance.
     *
     * @param Game $game
     * @param Authenticatable $user
     */
    public function __construct(Game $game, Authenticatable $user)
    {
        $this->game = $game;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return [DiscordChannel::class];
    }

    public function toDiscord($notifiable): DiscordMessage
    {
        return DiscordMessage::create(
            ":new: Nouveau jeu inscrit : **{$this->game->nom_jeu}** de {$this->user->name} !",
            [
                'type' => 'link',
                'url' => $this->game->getUrl(),
                'title' => $this->game->nom_jeu
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
