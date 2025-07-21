<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait StringTrait
{
    public function getUpperName()
    {
        return Str::upper($this->name);
    }
}
