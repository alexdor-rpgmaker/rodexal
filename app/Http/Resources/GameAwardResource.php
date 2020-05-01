<?php

namespace App\Http\Resources;

use App\Helpers\StringParser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameAwardResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $awardLevel = null;
        if ($this->is_declinaison) {
            switch ($this->pivot->is_vainqueur) {
                case 1:
                    $awardLevel = 'gold';
                    break;
                case 2:
                    $awardLevel = 'silver';
                    break;
                case 3:
                    $awardLevel = 'bronze';
                    break;
            }
        }

        return [
            'status' => $this->pivot->is_vainqueur > 0 ? 'awarded' : 'nominated',
            'award_level' => $awardLevel,
            'category_name' => StringParser::html($this->nom_categorie)
        ];
    }
}
