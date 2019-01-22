<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Word extends Model
{
    use Sluggable, SluggableScopeHelpers;

    public function getRouteKey()
    {
        return $this->slug;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'label'
            ]
        ];
    }
}
