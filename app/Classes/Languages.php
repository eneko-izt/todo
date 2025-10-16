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
            self::BASQUE => 'Euskera',
            self::ENGLISH => 'English',
            self::SPANISH => 'Spanish',
            self::FRENCH => 'French',
        ];
    }
}