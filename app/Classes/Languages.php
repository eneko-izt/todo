<?php

namespace App\Classes;

class Languages
{
    public const BASQUE = 'eu';
    public const ENGLISH = 'en';
    public const SPANISH = 'es';
    public const FRENCH = 'fr';

    public static function getAll()
    {
        return [
            self::BASQUE => __('Basque'),
            self::ENGLISH => __('English'),
            self::SPANISH => __('Spanish'),
            self::FRENCH => __('French'),
        ];
    }
}
