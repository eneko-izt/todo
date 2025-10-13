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

    'accepted'             => 'Le champ :attribute doit être accepté.',
    'active_url'           => 'Le champ :attribute n\'est pas une URL valide.',
    'after'                => 'Le champ :attribute doit être une date postérieure à :date.',
    'alpha'                => 'Le champ :attribute ne peut contenir que des lettres.',
    'alpha_dash'           => 'Le champ :attribute ne peut contenir que des lettres, des chiffres et des tirets (a-z, 0-9, -_).',
    'alpha_num'            => 'Le champ :attribute ne peut contenir que des lettres et des chiffres.',
    'array'                => 'Le champ :attribute doit être un tableau.',
    'before'               => 'Le champ :attribute doit être une date antérieure à :date.',
    'between'              => [
        'numeric' => 'Le champ :attribute doit être un valeur entre :min et :max.',
        'file'    => 'Le fichier :attribute doit peser entre :min et :max kilobytes.',
        'string'  => 'Le champ :attribute doit contenir entre :min et :max caractères.',
        'array'   => 'Le champ :attribute doit contenir entre :min et :max éléments.',
    ],
    'boolean'              => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed'            => 'Le champ de confirmation :attribute ne correspond pas.',
    'date'                 => 'Le champ :attribute ne correspond pas à une date valide.',
    'date_format'          => 'Le champ :attribute ne correspond pas au format de date :format.',
    'different'            => 'Les champs :attribute et :other doivent être différents.',
    'digits'               => 'Le champ :attribute doit être un nombre de :digits chiffres.',
    'digits_between'       => 'Le champ :attribute doit contenir entre :min et :max chiffres.',
    'email'                => 'Le champ :attribute ne correspond pas à une adresse e-mail valide.',
    'filled'               => 'Le champ :attribute est obligatoire.',
    'exists'               => 'Le champ :attribute n\'existe pas.',
    'image'                => 'Le champ :attribute doit être une image.',
    'in'                   => 'Le champ :attribute doit être égal à l\'un de ces valeurs :values',
    'integer'              => 'Le champ :attribute doit être un nombre entier.',
    'ip'                   => 'Le champ :attribute doit être une adresse IP valide.',
    'json'                 => 'Le champ :attribute doit être une chaîne de texte JSON valide.',
    'max'                  => [
        'numeric' => 'Le champ :attribute doit être :max au maximum.',
        'file'    => 'Le fichier :attribute doit peser :max kilobytes au maximum.',
        'string'  => 'Le champ :attribute doit contenir :max caractères au maximum.',
        'array'   => 'Le champ :attribute doit contenir :max éléments au maximum.',
    ],
    'mimes'                => 'Le champ :attribute doit être un fichier de type :values.',
    'min'                  => [
        'numeric' => 'Le champ :attribute doit avoir au moins :min.',
        'file'    => 'Le fichier :attribute doit peser au moins :min kilobytes.',
        'string'  => 'Le champ :attribute doit contenir au moins :min caractères.',
        'array'   => 'Le champ :attribute ne doit pas contenir plus de :min éléments.',
    ],
    'not_in'               => 'Le champ :attribute sélectionné est invalide.',
    'numeric'              => 'Le champ :attribute doit être un nombre.',
    'regex'                => 'Le format du champ :attribute est invalide.',
    'required'             => 'Le champ :attribute est obligatoire.',
    'required_if'          => 'Le champ :attribute est obligatoire lorsque le champ :other est :value.',
    'required_with'        => 'Le champ :attribute est obligatoire lorsque :values est présent.',
    'required_with_all'    => 'Le champ :attribute est obligatoire lorsque :values est présent.',
    'required_without'     => 'Le champ :attribute est obligatoire lorsque :values n\'est pas présent.',
    'required_without_all' => 'Le champ :attribute est obligatoire lorsque aucun champ :values n\'est présent.',
    'same'                 => 'Les champs :attribute et :other doivent correspondre.',
    'size'                 => [
        'numeric' => 'Le champ :attribute doit être :size.',
        'file'    => 'Le fichier :attribute doit peser :size kilobytes.',
        'string'  => 'Le champ :attribute doit contenir :size caractères.',
        'array'   => 'Le champ :attribute doit contenir :size éléments.',
    ],
    'string'               => 'Le champ :attribute doit contenir uniquement des caractères.',
    'timezone'             => 'Le champ :attribute doit contenir un fuseau horaire valide.',
    'unique'               => 'L\'élément :attribute est déjà utilisé.',
    'url'                  => 'Le format de :attribute ne correspond pas à celui d\'une URL valide.',

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
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
