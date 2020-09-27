<?php

namespace App\Services\Logic\Consult;

use App\Services\Logic\ConsultTrait;
use App\Services\Logic\Service;
use App\Validators\Consult as ConsultValidator;

class ConsultReply extends Service
{

    use ConsultTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $consult = $this->checkConsult($id);

        $validator = new ConsultValidator();

        $validator->checkReplyPriv($consult, $user);

        $answer = $validator->checkAnswer($post['answer']);

        $consult->update([
            'answer' => $answer,
            'reply_time' => time(),
        ]);

        return $consult;
    }

}
