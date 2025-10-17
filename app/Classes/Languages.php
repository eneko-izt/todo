<?php

namespace App\Classes;

class Languages
{
    public const BASQUE = 'eu';
    public const ENGLISH = 'en';
    public const SPANISH = 'es';
    public const FRENCH = 'fr';

    public const LOCALES = [self::BASQUE, self::ENGLISH, self::SPANISH, self::FRENCH];

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
