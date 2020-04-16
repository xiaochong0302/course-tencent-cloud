<?php

namespace App\Services\Frontend;

use App\Validators\Consult as ConsultValidator;

trait ConsultTrait
{

    public function checkConsult($id)
    {
        $validator = new ConsultValidator();

        return $validator->checkConsult($id);
    }

}
