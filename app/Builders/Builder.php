<?php

namespace App\Builders;

use Phalcon\Di\Injectable;

class Builder extends Injectable
{

    public function objects(array $items)
    {
        return kg_array_object($items);
    }

}
