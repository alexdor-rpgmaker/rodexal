<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => "Le champ :attribute doit être accepté.",
    'active_url' => "Le champ :attribute n'est pas une URL valide.",
    'after' => "Le champ :attribute doit être une date après :date.",
    'after_or_equal' => "Le champ :attribute doit être une date après :date, inclus.",
    'alpha' => "Le champ :attribute ne doit contenir seulement des lettres.",
    'alpha_dash' => "Le champ :attribute ne doit contenir que des lettres, chiffres, tirets et underscores.",
    'alpha_num' => "Le champ :attribute ne doit contenir que des lettres et chiffres.",
    'array' => "Le champ :attribute doit être un tableau.",
    'before' => "Le champ :attribute doit être une date précédant :date.",
    'before_or_equal' => "Le champ :attribute doit être une date égale ou précédant :date.",
    'between' => [
        'numeric' => "Le champ :attribute doit être entre :min et :max.",
        'file' => "Le champ :attribute doit peser entre :min et :max ko.",
        'string' => "Le champ :attribute doit avoir entre :min et :max caractères.",
        'array' => "Le champ :attribute doit contenir entre :min et :max éléments.",
    ],
    'boolean' => "Le champ :attribute doit être vrai ou faux.",
    'confirmed' => "Le champ de confirmation de :attribute ne correspond pas.",
    'date' => "Le champ :attribute n'est pas une date valide.",
    'date_equals' => "Le champ :attribute doit être égal à :date.",
    'date_format' => "Le champ :attribute ne correspond pas au format :format.",
    'different' => "Les champs :attribute et :other doit être different.",
    'digits' => "Le champ :attribute doit contenir :digits chiffres.",
    'digits_between' => "Le champ :attribute doit avoir entre :min et :max chiffres.",
    'dimensions' => "L'image du champ :attribute ne respecte pas les dimensions requises.",
    'distinct' => "Un autre champ :attribute a déjà cette valeur en base de données.",
    'email' => "Le champ :attribute doit être une adresse e-mail valide.",
    'exists' => "L'élément sélectionné dans :attribute est invalide.",
    'file' => "Le champ :attribute doit être un fichier.",
    'filled' => "Le champ :attribute doit être rempli.",
    'gt' => [
        'numeric' => "Le champ :attribute doit être supérieur à :value.",
        'file' => "Le champ :attribute doit peser plus de :value ko.",
        'string' => "Le champ :attribute doit contenir plus de :value caractères.",
        'array' => "Le champ :attribute contenir plus de :value éléments.",
    ],
    'gte' => [
        'numeric' => "Le champ :attribute doit être supérieur ou égal à :value.",
        'file' => "Le champ :attribute doit peser au moins :value ko.",
        'string' => "Le champ :attribute doit avoir au moins :value caractères.",
        'array' => "Le champ :attribute doit contenir au moins :value éléments.",
    ],
    'image' => "Le champ :attribute doit être une image.",
    'in' => "L'élément sélectionné dans :attribute est invalide.",
    'in_array' => "Le champ :attribute ne figure pas dans :other.",
    'integer' => "Le champ :attribute doit être un entier.",
    'ip' => "Le champ :attribute doit être une adresse IP valide.",
    'ipv4' => "Le champ :attribute doit être une adresse IPv4 valide.",
    'ipv6' => "Le champ :attribute doit être une adresse IPv6 valide.",
    'json' => "Le champ :attribute doit être un JSON valide.",
    'lt' => [
        'numeric' => "Le champ :attribute doit être inférieur à :value.",
        'file' => "Le champ :attribute doit peser moins de :value ko.",
        'string' => "Le champ :attribute doit contenir moins de :value caractères.",
        'array' => "Le champ :attribute doit contenir moins de :value éléments.",
    ],
    'lte' => [
        'numeric' => "Le champ :attribute doit être inférieur ou égal à :value.",
        'file' => "Le champ :attribute doit peser au maximum :value ko.",
        'string' => "Le champ :attribute doit contenir au maximum :value caractères.",
        'array' => "Le champ :attribute ne doit pas contenir plus de :value éléments.",
    ],
    'max' => [
        'numeric' => "Le champ :attribute ne doit pas être plus grand que :max.",
        'file' => "Le champ :attribute ne doit pas peser plus que :max ko.",
        'string' => "Le champ :attribute ne doit pas contenir plus de :max caractères.",
        'array' => "Le champ :attribute ne doit pas contenir plus de :max éléments.",
    ],
    'mimes' => "Le type du fichier :attribute doit être un des suivants : :values.",
    'mimetypes' => "Le type du fichier :attribute doit être un des suivants : :values.",
    'min' => [
        'numeric' => "Le champ :attribute doit être supérieur à :min.",
        'file' => "Le champ :attribute doit peser au moins :min ko.",
        'string' => "Le champ :attribute doit contenir au moins :min caractères.",
        'array' => "Le champ :attribute doit contenir au moins :min éléments.",
    ],
    'not_in' => "L'élément sélectionné dans :attribute est invalide.",
    'not_regex' => "Le format du champ \":attribute\" est invalide.",
    'numeric' => "Le champ :attribute doit être un nombre.",
    'present' => "Le champ :attribute doit être présent.",
    'regex' => "Le format du champ :attribute est invalide.",
    'required' => "Le champ :attribute est obligatoire.",
    'required_if' => "Le champ :attribute est obligatoire quand :other is :value.",
    'required_unless' => "Le champ :attribute est obligatoire à moins que la valeur :other se trouve dans :values.",
    'required_with' => "Le champ :attribute est obligatoire quand :values est présent.",
    'required_with_all' => "Le champ :attribute est obligatoire quand :values sont présents.",
    'required_without' => "Le champ :attribute est obligatoire quand :values n'est pas présent.",
    'required_without_all' => "Le champ :attribute est obligatoire quand aucun des :values n'est présent.",
    'same' => "Le champ :attribute et :other doivent correspondre.",
    'size' => [
        'numeric' => "Le champ :attribute doit être égal à :size.",
        'file' => "Le champ :attribute doit peser :size ko.",
        'string' => "Le champ :attribute doit contenir :size caractères.",
        'array' => "Le champ :attribute doit contenir :size éléments.",
    ],
    'starts_with' => "Le champ :attribute doit commencer par un des éléments suivants: :values",
    'string' => "Le champ :attribute doit être une chaîne de caractères.",
    'timezone' => "Le champ :attribute doit être un fuseau horaire valide.",
    'unique' => "Le champ :attribute a déjà été choisi.",
    'uploaded' => "Le champ :attribute n'a pas réussi à s'uploader.",
    'url' => "Le format du champ :attribute est invalide.",
    'uuid' => "Le champ :attribute doit être un UUID valide.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => "custom-message",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'finalThought' => 'Verdict',
    ],

];
