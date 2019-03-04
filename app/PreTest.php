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
            'label' => "Le jeu n'est pas autonome",
            'word' => 'autonome'
        ],
        [
            'id' => 'notLaunchable',
            'label' => 'Impossible de lancer le jeu',
            'fieldDescription' =>
            'Le jeu est trop lourd, mal optimisé, etc... Vous ne parvenez pas à faire tourner le jeu sur votre ordinateur.'
        ],
        [
            'id' => 'blockingBug',
            'label' => 'Bug bloquant inévitable',
            'fieldDescription' =>
            'Impossible d\'avancer dans le jeu à partir de ce bug, impossible de contourner ce bug.'
        ],
        [
            'id' => 'severalBugs',
            'label' => 'Présence abusive de bugs non bloquants',
            'fieldDescription' =>
            'Il est possible d\'avancer dans le jeu mais le grand nombre de bugs montrent que le jeu n\'a pas été testé par son créateur et/ou qu\'il y a de trop gros problèmes dans la réalisation du jeu.'
        ],
        [
            'id' => 'spellingMistakes',
            'label' => "Nombre abusif de fautes d'orthographe",
            'fieldDescription' =>
            'Lorsque la lecture du jeu devient un supplice à cause des fautes.'
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

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function questionnaireHasActivatedFields()
    {
        $questionnaire_flattened = data_get($this->questionnaire, '*.activated');
        return array_first($questionnaire_flattened, function ($value) {
            return !empty($value);
        });
    }
}
