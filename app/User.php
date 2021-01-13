<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public function isJury()
    {
        return $this->rank >= 4;
    }


    public function isAdmin()
    {
        return $this->rank >= 6;
    }

    public function rankName()
    {
        $ranks = ["Invité", "Membre", "Concurrent", "Ambassadeur", "Juré", "Modérateur", "Admin", "Webmaster"];
        return $ranks[$this->rank];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'rank', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'rank'
    ];
}
