<?php

namespace App\Services\Frontend\Consult;

use App\Models\Consult as ConsultModel;
use App\Repos\User as UserRepo;
use App\Services\Frontend\ConsultTrait;
use App\Services\Frontend\Service as FrontendService;

class ConsultInfo extends FrontendService
{

    use ConsultTrait;

    public function handle($id)
    {
        $consult = $this->checkConsult($id);

        return $this->handleConsult($consult);
    }

    protected function handleConsult(ConsultModel $consult)
    {
        $result = [
            'id' => $consult->id,
            'question' => $consult->question,
            'answer' => $consult->answer,
            'like_count' => $consult->like_count,
            'create_time' => $consult->create_time,
            'update_time' => $consult->update_time,
        ];

        $userRepo = new UserRepo();

        $owner = $userRepo->findById($consult->user_id);

        $result['user'] = [
            'id' => $owner->id,
            'name' => $owner->name,
            'avatar' => $owner->avatar,
        ];

        return $result;
    }

}
