<?php

namespace App\Former;

class CarouselImage extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'carrousel';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'is_active' => true,
        'ordre' => 1
    ];
}
