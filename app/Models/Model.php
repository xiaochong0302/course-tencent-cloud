<?php

namespace App\Models;

class Model extends \Phalcon\Mvc\Model
{

    public function initialize()
    {
        $this->setup([
            'notNullValidations' => false,
        ]);

        $this->useDynamicUpdate(true);
    }

}