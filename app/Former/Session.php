<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * @var string
     */
    protected $connection = 'former_app_database';
    /**
     * @var string
     */
    protected $table = 'sessions';
    /**
     * @var string
     */
    protected $primaryKey = 'id_session';
    /**
     * @var bool
     */
    public $timestamps = false;
}
