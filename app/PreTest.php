<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreTest extends Model
{
    protected $table = 'pre_tests';

    protected $casts = [
        'questionnaire' => 'array'
    ];

    const FIELDS = [
        [
            'id' => 'notAutonomous',
            'label' => "Le jeu n'est pas autonome"
        ],
        [
            'id' => 'notLaunchable',
            'label' => 'Impossible de lancer le jeu'
        ],
        [
            'id' => 'blockingBug',
            'label' => 'Bug bloquant inévitable'
        ],
        [
            'id' => 'severalBugs',
            'label' => 'Présence abusive de bugs non bloquants'
        ],
        [
            'id' => 'spellingMistakes',
            'label' => "Nombre abusif de fautes d'orthographe"
        ],
        [
            'id' => 'tooHard',
            'label' => 'Difficulté abusive/mal calibrée',
            'fieldDescription' =>
            'Nombre de game over injuste par heure de jeu, mauvaise maniabilité, explications manquantes...'
        ],
        [
            'id' => 'tooShort',
            'label' => 'Jeu trop court',
            'fieldDescription' =>
            'La totalité du jeu est observable en moins de 30 minutes'
        ],
        [
            'id' => 'unplayableAlone',
            'label' => "Impossible d'apprécier seul la majeure partie du jeu",
            'fieldDescription' => 'Le multijoueur est nécessaire'
        ]
    ];
}
