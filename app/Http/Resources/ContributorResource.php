<?php

namespace App\Http\Resources;

use App\Helpers\StringParser;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContributorResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userId = $this->id_membre ? $this->id_membre : null;
        $rank = $userId && $this->member ? $this->member->rank : null;
        $username = $userId && $this->member ? $this->member->pseudo : $this->nom_membre;

        return [
            'id' => $userId,
            'rank' => $rank,
            'username' => StringParser::html($username),
            'role' => StringParser::parseOrNullify($this->role)
        ];
    }
}
