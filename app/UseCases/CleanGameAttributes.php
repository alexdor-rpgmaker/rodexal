<?php

namespace App\UseCases;

use App\Former\Game;
use App\Helpers\StringParser;
use App\Http\Resources\GameResource;

class CleanGameAttributes
{
    /**
     * @param Game|GameResource $game
     * @param bool $cleanAssociations
     * @return Game|GameResource
     */
    public static function perform($game, $cleanAssociations = false)
    {
        $game = self::cleanAttributes($game, [
            'nom_jeu', 'taille', 'genre_jeu', 'theme', 'duree', 'support', 'site_officiel', 'groupe'
        ]);

        if ($cleanAssociations) {
            $game->contributors->map(function ($contributor) {
                $contributor->nom_membre = StringParser::parseOrNullify($contributor->nom_membre);
                if ($contributor->member) {
                    $contributor->member->pseudo = StringParser::parseOrNullify($contributor->member->pseudo);
                }
            });

            $game->screenshots->map(function ($screenshot) {
                $screenshot->title = StringParser::parseOrNullify($screenshot->title);
            });
        }

        return $game;
    }

    private static function cleanAttributes($object, $attributes)
    {
        foreach ($attributes as $attribute) {
            $object->{$attribute} = StringParser::parseOrNullify($object->{$attribute});
        }
        return $object;
    }
}
