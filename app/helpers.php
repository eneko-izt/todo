<?php

//TODO: sortu karpeta bat app barruan Helpers, eta hor barrun gorde, bestela Modeloekin nahasten ari gara
function is_admin()
{
    return auth()->check() && auth()->user()->hasRoleName('admin');
}
