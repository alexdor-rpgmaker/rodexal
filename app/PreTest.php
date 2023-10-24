<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;

class PreTest extends MainModel
{
    use HasFactory;

    protected $table = 'pre_tests';

    protected $casts = [
        'questionnaire' => 'array'
    ];

    // Fields for pre-tests between 2019 and 2022
    const QCM_FIELDS = [
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
                'La totalité du jeu est observable en moins de 20 minutes'
        ],
        [
            'id' => 'unplayableAlone',
            'label' => "Impossible d'apprécier seul la majeure partie du jeu",
            'fieldDescription' => 'Le multijoueur est nécessaire'
        ],
        [
            'id' => 'languageUnknown',
            'label' => "Langue inconnue",
            'fieldDescription' => 'Le jeu n\'est pas dans une langue connue du juré'
        ]
    ];

    // Fields for pre-tests of Session 2023-2024
    const PRE_QUALIFICATIONS_DISQUALIFYING_FIELDS = [
        [
            'id' => 'abusiveBugs',
            'label' => "Présence de bugs abusifs et/ou bloquants",
            'fieldDescription' =>
                "Si le jeu n'est pas jouable parce que vous êtes bloqué à cause d'un fichier manquant, d'un bug vous bloquant dans un mur, qu'une cinématique se bloque ou qu'un event ne vous permet plus de continuer, veuillez cocher cette case. Si vous avez des bugs non bloquants mais abusifs et très nombreux, veuillez également cocher cette case."
        ],
        [
            'id' => 'tooShort',
            'label' => 'Jeu ne respectant pas la durée minimale de 30 minutes',
            'fieldDescription' =>
                "Si, en dehors des cinématiques de début de jeu, celui-ci dure moins de 30 minutes, veuillez cocher cette case. Cette section ne s'applique pas forcément pour tout. Si un combat dans un jeu de combat se termine après 5 minutes, celui-ci n'est pour autant pas terminé. C'est si la découverte totale du jeu se termine en moins de 30 minutes qu'il peut se faire disqualifier. C'est à votre jugement de faire le reste."
        ],
        [
            'id' => 'notForWindows',
            'label' => "Jeu n'étant pas jouable sur ordinateur ou lançable sur Windows, impossible de lancer le jeu de manière générale",
            'fieldDescription' =>
                "Si un jeu s'avère être un jeu console ou bien que le jeu ne se lance que sur Mac ou Linux, veuillez cocher cette case.",
        ],
        [
            'id' => 'notAutonomous',
            'label' => "Jeu non autonome",
            'fieldDescription' =>
                "Si un jeu nécessite d'installer des polices d'écriture, des RGSS, des logiciels externes, veuillez cocher cette case.",
            'word' => 'autonome'
        ],
        [
            'id' => 'noDownloadLink',
            'label' => 'Aucun lien de téléchargement',
            'fieldDescription' =>
                "Si un jeu n'a aucun lien de téléchargement valide, veuillez cocher cette case."
        ],
        [
            'id' => 'illegalContent',
            'label' => 'Contenu illégal ou illicite',
            'fieldDescription' =>
                "Si le jeu fait l'apologie du viol, du meurtre, discrimine de manière volontaire, contient de la pornographie ou pédopornographie, si le jeu est de quelconque manière illégal ou illicite, veuillez cocher cette case et avertir les administrateurs et/ou modérateurs."
        ],
        [
            'id' => 'plagiarism',
            'label' => 'Absence totale de crédits, jeu volé ou plagiat',
            'fieldDescription' =>
                "Si le jeu n'est pas un jeu de l'auteur, que c'est une copie d'un autre jeu ou que le jeu n'a aucun crédits, veuillez cocher cette case."
        ],
        [
            'id' => 'tooHard',
            'label' => 'Difficulté abusive et mal calibrée',
            'fieldDescription' =>
                "Si le jeu est beaucoup trop dur, que vous ne pouvez pas passer un niveau facile ou que vous trouvez que la difficulté globale est très mal calibrée, veuillez cocher cette case. C'est à votre libre arbitre de décider."
        ]
    ];

    // Fields for pre-tests of Session 2023-2024
    const PRE_QUALIFICATIONS_NOT_DISQUALIFYING_FIELDS = [
        [
            'id' => 'spellingMistakes',
            'label' => "Présence abusive de fautes d'orthographe",
            'fieldDescription' =>
                "Si le jeu contient énormément de fautes d'orthographe, le jeu ne peut pas être disqualifié, cependant, veuillez cocher cette case si selon vous celles-ci sont trop abusives et dérangeantes."
        ],
        [
            'id' => 'unusualController',
            'label' => "Jouabilité à la manette quasi obligatoire",
            'fieldDescription' =>
                "Si le jeu a été pensé pour être joué quasi uniquement à la manette, mais pas avec un clavier ou une souris, veuillez cocher cette case."
        ],
        [
            'id' => 'painfulHandling',
            'label' => "Maniabilité très contraignante",
            'fieldDescription' =>
                "Si le jeu est très compliqué à manier, que vous avez l'impression de conduire un semi-remorque ou bien que les fonctionnalités sont compliquées à assimiler, veuillez cocher cette case."
        ],
        [
            'id' => 'wrongAgeCategory',
            'label' => "Mauvaise catégorie d'âge",
            'fieldDescription' =>
                "Si le jeu est catégorisé PEGI 3, 7, 12, ou 16 et qu'il ne respecte pas exactement la législation PEGI, veuillez cocher cette case. Les catégories PEGI sont accessibles dans le règlement du concours."
        ],
        [
            'id' => 'partialCredits',
            'label' => "Crédits partiels",
            'fieldDescription' =>
                "Si le jeu a des crédits mais que vous savez qu'ils ne sont pas complets, veuillez cocher cette case."
        ],
        [
            'id' => 'annoyingLag',
            'label' => "Présence de lags",
            'fieldDescription' =>
                "Si le jeu ne se joue pas avec des FPS convenables et que c'est gênant, veuillez cocher cette case."
        ]
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function explanationsCount(): int
    {
        $count = 0;
        foreach (self::QCM_FIELDS as $field) {
            if (Arr::has($this->questionnaire, $field['id'])) {
                if (!empty($this->questionnaire[$field['id']]['explanation'])) {
                    $count++;
                }
            }
        }
        if (!empty($this->final_thought_explanation)) {
            $count++;
        }

        return $count;
    }

    public function questionnaireHasActivatedFields()
    {
        $questionnaire_flattened = data_get($this->questionnaire, '*.activated');
        return Arr::first($questionnaire_flattened, function ($value) {
            return !empty($value);
        });
    }
}
