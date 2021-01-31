<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Test extends FormerModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'tests';
    /**
     * @var string
     */
    protected $primaryKey = 'id_test';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_test' => 1,
        'is_video' => false,
        'reviewer_id' => null
    ];
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $dates = [
        'date_modification',
        'reviewed_at',
    ];

    public function suite()
    {
        return $this->belongsTo('App\Former\TestSuite', 'id_serie');
    }

    public function game()
    {
        return $this->belongsTo('App\Former\Game', 'id_jeu');
    }

    public function juror()
    {
        return $this->belongsTo('App\Former\Juror', 'id_jury');
    }
}
