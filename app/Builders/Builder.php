<?php

namespace App\Builders;

use Phalcon\Mvc\User\Component as UserComponent;

class Builder extends UserComponent
{

    public function arrayToObject($array)
    {
        $result = kg_array_object($array);

        return $result;
    }

}
