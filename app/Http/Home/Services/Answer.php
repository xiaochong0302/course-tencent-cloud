<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
