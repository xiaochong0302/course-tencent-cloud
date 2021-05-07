<?php

namespace App\Services\Logic;

use App\Validators\Answer as AnswerValidator;

trait AnswerTrait
{

    public function checkAnswer($id)
    {
        $validator = new AnswerValidator();

        return $validator->checkAnswer($id);
    }

}
