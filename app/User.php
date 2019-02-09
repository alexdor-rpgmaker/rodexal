<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public function isAdmin() {
        return $this->rank >= 6;
    }

    public function rankName() {
        $ranks = ["Invité", "Membre", "Concurrent", "Ambassadeur", "Juré", "Modérateur", "Admin", "Webmaster"];
        return $ranks[$this->rank];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
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
