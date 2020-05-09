<?php

namespace App\Services\Frontend\Consult;

use App\Models\Consult as ConsultModel;
use App\Models\ConsultVote as ConsultVoteModel;
use App\Models\User as UserModel;
use App\Repos\ConsultVote as ConsultVoteRepo;
use App\Repos\User as UserRepo;
use App\Services\Frontend\ConsultTrait;
use App\Services\Frontend\Service;

class ConsultInfo extends Service
{

    use ConsultTrait;

    public function handle($id)
    {
        $consult = $this->checkConsult($id);

        $user = $this->getCurrentUser();

        return $this->handleConsult($consult, $user);
    }

    protected function handleConsult(ConsultModel $consult, UserModel $user)
    {
        $result = [
            'id' => $consult->id,
            'question' => $consult->question,
            'answer' => $consult->answer,
            'agree_count' => $consult->agree_count,
            'oppose_count' => $consult->oppose_count,
            'create_time' => $consult->create_time,
            'update_time' => $consult->update_time,
        ];

        $me = [
            'agreed' => 0,
            'opposed' => 0,
        ];

        if ($user->id > 0) {

            $voteRepo = new ConsultVoteRepo();

            $vote = $voteRepo->findConsultVote($consult->id, $user->id);

            if ($vote) {
                $me['agreed'] = $vote->type == ConsultVoteModel::TYPE_AGREE ? 1 : 0;
                $me['opposed'] = $vote->type == ConsultVoteModel::TYPE_OPPOSE ? 1 : 0;
            }
        }

        $userRepo = new UserRepo();

        $owner = $userRepo->findById($consult->user_id);

        $result['owner'] = [
            'id' => $owner->id,
            'name' => $owner->name,
            'avatar' => $owner->avatar,
        ];

        $result['me'] = $me;

        return $result;
    }

}
