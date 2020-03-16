<?php

namespace App\Builders;

use Phalcon\Mvc\User\Component;

class Builder extends Component
{

    public function arrayToObject($array)
    {
        $result = kg_array_object($array);

        return $result;
    }

}
