<?php

namespace App\Services\Frontend\Consult;

use App\Services\Frontend\ConsultTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Consult as ConsultValidator;

class ConsultUpdate extends FrontendService
{

    use ConsultTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $consult = $this->checkConsult($id);

        $validator = new ConsultValidator();

        $validator->checkOwner($user->id, $consult->owner_id);

        $validator->checkConsultEdit($consult);

        $data = [];

        if (isset($post['question'])) {
            $data['question'] = $validator->checkQuestion($post['question']);
        }

        if (isset($post['private'])) {
            $data['private'] = $validator->checkPrivateStatus($post['private']);
        }

        $consult->update($data);

        return $consult;
    }

}
