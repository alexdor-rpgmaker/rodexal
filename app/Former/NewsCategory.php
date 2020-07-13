<?php

namespace App\Former;

class NewsCategory extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'news_categories';
    /**
     * @var string
     */
    protected $primaryKey = 'id_categorie';

    public function categories()
    {
        return $this->belongsToMany('App\Former\NewsCategory', 'news_categories_pivot', 'id_news', 'id_categorie');
    }
}
