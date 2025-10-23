<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait BasicTrait
{
    public function getUpperName()
    {
        return Str::upper($this->name);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
