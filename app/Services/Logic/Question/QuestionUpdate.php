<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Question;

use App\Models\Question as QuestionModel;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Question as QuestionValidator;

class QuestionUpdate extends LogicService
{

    use QuestionTrait;
    use QuestionDataTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $question = $this->checkQuestion($id);

        $validator = new QuestionValidator();

        $user = $this->getLoginUser();

        $validator->checkOwner($user->id, $question->owner_id);

        $validator->checkIfAllowEdit($question);

        $data = $this->handlePostData($post);

        if ($question->published == QuestionModel::PUBLISH_REJECTED) {
            $data['published'] = QuestionModel::PUBLISH_PENDING;
        }

        $question->update($data);

        if (isset($post['xm_tag_ids'])) {
            $this->saveTags($question, $post['xm_tag_ids']);
        }

        $this->saveDynamicAttrs($question);

        $this->eventsManager->fire('Question:afterUpdate', $this, $question);

        return $question;
    }

}
