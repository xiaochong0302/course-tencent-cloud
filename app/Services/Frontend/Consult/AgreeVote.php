<?php

namespace App\Services\Frontend\Consult;

use App\Models\ConsultVote as ConsultVoteModel;
use App\Repos\ConsultVote as ConsultVoteRepo;
use App\Services\Frontend\ConsultTrait;
use App\Services\Frontend\Service;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class AgreeVote extends Service
{

    use ConsultTrait, VoteTrait;

    public function handleAgreeVote($id)
    {
        $consult = $this->checkConsult($id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkConsultVoteLimit($user);

        $consultVoteRepo = new ConsultVoteRepo();

        $consultVote = $consultVoteRepo->findConsultVote($consult->id, $user->id);

        if (!$consultVote) {

            $consultVote = new ConsultVoteModel();

            $consultVote->consult_id = $consult->id;
            $consultVote->user_id = $user->id;
            $consultVote->type = ConsultVoteModel::TYPE_AGREE;

            $consultVote->create();

            $this->incrAgreeCount($consult);

        } else {

            if ($consultVote->type == ConsultVoteModel::TYPE_AGREE) {

                $consultVote->type = ConsultVoteModel::TYPE_NONE;

                $this->decrAgreeCount($consult);

            } elseif ($consultVote->type == ConsultVoteModel::TYPE_OPPOSE) {

                $consultVote->type = ConsultVoteModel::TYPE_AGREE;

                $this->incrAgreeCount($consult);

                $this->decrOpposeCount($consult);

            } elseif ($consultVote->type == ConsultVoteModel::TYPE_NONE) {

                $consultVote->type = ConsultVoteModel::TYPE_AGREE;

                $this->incrAgreeCount($consult);
            }

            $consultVote->update();
        }

        $this->incrUserDailyConsultVoteCount($user);

        return $consult;
    }

}
