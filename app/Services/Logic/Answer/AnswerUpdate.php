<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Answer;

use App\Services\Logic\AnswerTrait;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;
use App\Validators\Answer as AnswerValidator;

class AnswerUpdate extends LogicService
{

    use ClientTrait;
    use QuestionTrait;
    use AnswerTrait;
    use AnswerDataTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $answer = $this->checkAnswer($id);

        $user = $this->getLoginUser();

        $validator = new AnswerValidator();

        $validator->checkOwner($user->id, $answer->owner_id);

        $validator->checkIfAllowEdit($answer);

        $data = $this->handlePostData($post);

        $answer->update($data);

        $this->saveDynamicAttrs($answer);

        $this->eventsManager->fire('Answer:afterUpdate', $this, $answer);

        return $answer;
    }

}
