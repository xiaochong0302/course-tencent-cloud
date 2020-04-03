<?php

namespace App\Builders;

use Phalcon\Mvc\User\Component;

class Builder extends Component
{

    public function arrayToObject($array)
    {
        return kg_array_object($array);
    }

}
