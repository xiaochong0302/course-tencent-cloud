<?php

namespace App\Builders;

use Phalcon\Mvc\User\Component;

class Builder extends Component
{

    public function objects(array $items)
    {
        return kg_array_object($items);
    }

}
