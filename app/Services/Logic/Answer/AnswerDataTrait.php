<?php

namespace App\Services\Logic\Answer;

use App\Models\Answer as AnswerModel;
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

    protected function saveDynamicAttrs(AnswerModel $answer)
    {
        $answer->cover = kg_parse_first_content_image($answer->content);

        $answer->summary = kg_parse_summary($answer->content);

        $answer->update();
    }

}
