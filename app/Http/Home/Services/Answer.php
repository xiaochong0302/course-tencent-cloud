<?php

namespace App\Http\Home\Services;

use App\Models\Answer as AnswerModel;
use App\Services\Logic\AnswerTrait;
use App\Services\Logic\Service as LogicService;

class Answer extends LogicService
{

    use AnswerTrait;

    public function getAnswerModel()
    {
        return new AnswerModel();
    }

    public function getAnswer($id)
    {
        return $this->checkAnswer($id);
    }

}
