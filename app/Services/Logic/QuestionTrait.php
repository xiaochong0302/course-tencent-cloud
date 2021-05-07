<?php

namespace App\Services\Logic;

use App\Validators\Question as QuestionValidator;

trait QuestionTrait
{

    public function checkQuestion($id)
    {
        $validator = new QuestionValidator();

        return $validator->checkQuestion($id);
    }

    public function checkQuestionCache($id)
    {
        $validator = new QuestionValidator();

        return $validator->checkQuestionCache($id);
    }

}
