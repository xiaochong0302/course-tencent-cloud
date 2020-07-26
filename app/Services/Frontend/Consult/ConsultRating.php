<?php

namespace App\Services\Frontend\Consult;

use App\Services\Frontend\ConsultTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Consult as ConsultValidator;

class ConsultRating extends FrontendService
{

    use ConsultTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $consult = $this->checkConsult($id);

        $validator = new ConsultValidator();

        $validator->checkOwner($user->id, $consult->user_id);

        $validator->checkIfAllowRate($consult);

        $rating = $validator->checkRating($post['rating']);

        $consult->rating = $rating;

        $consult->update();

        return $consult;
    }

}
