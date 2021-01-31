<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ForumCategory extends FormerModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'forum_categories';
    /**
     * @var string
     */
    protected $primaryKey = 'id_categorie';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_categorie' => 1,
        'permission' => 0,
        'position' => 1,
    ];
}
