<?php

function is_admin()
{
    return auth()->check() && auth()->user()->hasRoleName('admin');
}