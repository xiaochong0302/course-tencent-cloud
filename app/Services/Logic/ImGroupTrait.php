<?php

namespace App\Services\Logic;

use App\Validators\ImGroup as ImGroupValidator;

trait ImGroupTrait
{

    public function checkGroup($id)
    {
        $validator = new ImGroupValidator();

        return $validator->checkGroup($id);
    }

}
