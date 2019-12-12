<?php

namespace App\Transformers;

use Phalcon\Mvc\User\Component as UserComponent;

class Transformer extends UserComponent
{

    public function arrayToObject($array)
    {
        $result = kg_array_object($array);

        return $result;
    }

}
