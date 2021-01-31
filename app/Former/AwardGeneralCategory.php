<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AwardGeneralCategory extends FormerModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'awards_categories_series';
    /**
     * @var string
     */
    protected $primaryKey = 'id_serie';
}
