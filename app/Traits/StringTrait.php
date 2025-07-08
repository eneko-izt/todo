<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait StringTrait 
{
    public function getUpper($value)
    {
        return Str::upper($value);
    }
}