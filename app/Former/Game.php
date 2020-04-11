<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /**
     * @var string
     */
    protected $connection = 'former_app_database';
    /**
     * @var string
     */
    protected $table = 'jeux';
    /**
     * @var string
     */
    protected $primaryKey = 'id_jeu';
    /**
     * @var bool
     */
    public $timestamps = false;
}
