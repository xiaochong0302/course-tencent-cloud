<?php

namespace App\Services\Frontend\Consult;

use App\Services\Frontend\ConsultTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Consult as ConsultValidator;

class ConsultReply extends FrontendService
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
