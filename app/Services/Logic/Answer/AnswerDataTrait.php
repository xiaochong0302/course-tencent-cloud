<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Answer;

use App\Models\Answer as AnswerModel;
use App\Models\User as UserModel;
use App\Traits\Client as ClientTrait;
use App\Validators\Answer as AnswerValidator;

trait AnswerDataTrait
{

    use ClientTrait;

    protected function handlePostData($post)
    {
        $data = [];

        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();

        $validator = new AnswerValidator();

        $data['content'] = $validator->checkContent($post['content']);

        return $data;
    }

    protected function getPublishStatus(UserModel $user)
    {
        return $user->answer_count > 2 ? AnswerModel::PUBLISH_APPROVED : AnswerModel::PUBLISH_PENDING;
    }

    protected function saveDynamicAttrs(AnswerModel $answer)
    {
        $answer->cover = kg_parse_first_content_image($answer->content);

        $answer->summary = kg_parse_summary($answer->content);

        $answer->update();
    }

}
