<?php

namespace App\Former;

class PartnerWebsite extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'partenariat';
    /**
     * @var string
     */
    protected $primaryKey = 'id_partenariat';
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $dates = [
        'date_demande',
    ];
}
