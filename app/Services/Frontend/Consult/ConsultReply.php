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

        $validator->checkTeacher($consult, $user);

        $consult->answer = $validator->checkAnswer($post['answer']);
        $consult->reply_time = time();
        $consult->update();

        return $consult;
    }

}
