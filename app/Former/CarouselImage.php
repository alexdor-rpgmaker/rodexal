<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarouselImage extends FormerModel
{
    use HasFactory;

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
