<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsPost extends FormerModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'news';
    /**
     * @var string
     */
    protected $primaryKey = 'id_news';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_news' => 1,
        'nb_commentaires' => 0,
//        'origine' ???
        'is_blog' => false,
    ];
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $dates = [
        'date_creation_news',
        'date_validation_news',
    ];

    public function member()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre');
    }

    public function news()
    {
        return $this->belongsToMany('App\Former\NewsPost', 'news_categories_pivot', 'id_categorie', 'id_news');
    }
}
