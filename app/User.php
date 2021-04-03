<?php

namespace App;

use App\Former\Member;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

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

    public function isJury(): bool
    {
        return $this->rank >= 4;
    }

    public function isAdmin(): bool
    {
        return $this->rank >= 6;
    }

    public function rankName(): string
    {
        return Member::RANKS[$this->rank];
    }

    public static function signInUrl(): string
    {
        return url('/oauth/callback');
    }

    public static function signUpUrl(): string
    {
        $formerAppUrl = env('FORMER_APP_URL');
        return "$formerAppUrl/?p=inscription";
    }
}
