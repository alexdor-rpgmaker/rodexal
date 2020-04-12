<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Model;

class FormerModel extends Model
{
    /**
     * @var string
     */
    protected $connection = 'former_app_database';
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
