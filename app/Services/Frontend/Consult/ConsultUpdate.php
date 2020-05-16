<?php

namespace App\Services\Frontend\Consult;

use App\Services\Frontend\ConsultTrait;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Consult as ConsultValidator;

class ConsultUpdate extends FrontendService
{

    use CourseTrait, ConsultTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $consult = $this->checkConsult($id);

        $validator = new ConsultValidator();

        $validator->checkOwner($user->id, $consult->user_id);

        $question = $validator->checkQuestion($post['question']);

        $consult->question = $question;

        $consult->update();

        return $consult;
    }

}
