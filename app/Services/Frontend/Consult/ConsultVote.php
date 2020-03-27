<?php

namespace App\Services\Frontend\Consult;

use App\Models\ConsultVote as ConsultVoteModel;
use App\Models\User as UserModel;
use App\Repos\ConsultVote as ConsultVoteRepo;
use App\Services\Frontend\ConsultTrait;
use App\Services\Frontend\Service;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class ConsultVote extends Service
{

    use ConsultTrait;

    public function agree($id)
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

            $consult->agree_count += 1;

        } else {

            if ($consultVote->type == ConsultVoteModel::TYPE_AGREE) {

                $consultVote->type = ConsultVoteModel::TYPE_NONE;

                $consult->agree_count -= 1;

            } elseif ($consultVote->type == ConsultVoteModel::TYPE_OPPOSE) {

                $consultVote->type = ConsultVoteModel::TYPE_AGREE;

                $consult->agree_count += 1;
                $consult->oppose_count -= 1;

            } elseif ($consultVote->type == ConsultVoteModel::TYPE_NONE) {

                $consultVote->type = ConsultVoteModel::TYPE_AGREE;

                $consult->agree_count += 1;
            }

            $consultVote->update();
        }

        $consult->update();

        $this->incrUserDailyConsultVoteCount($user);

        return $consult;
    }

    public function oppose($id)
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
            $consultVote->type = ConsultVoteModel::TYPE_OPPOSE;

            $consultVote->create();

            $consult->oppose_count += 1;

        } else {

            if ($consultVote->type == ConsultVoteModel::TYPE_AGREE) {

                $consultVote->type = ConsultVoteModel::TYPE_OPPOSE;

                $consult->agree_count -= 1;
                $consult->oppose_count += 1;

            } elseif ($consultVote->type == ConsultVoteModel::TYPE_OPPOSE) {

                $consultVote->type = ConsultVoteModel::TYPE_NONE;

                $consult->oppose_count -= 1;

            } elseif ($consultVote->type == ConsultVoteModel::TYPE_NONE) {

                $consultVote->type = ConsultVoteModel::TYPE_OPPOSE;

                $consult->oppose_count += 1;
            }

            $consultVote->update();
        }

        $consult->update();

        $this->incrUserDailyConsultVoteCount($user);

        return $consult;
    }

    protected function incrUserDailyConsultVoteCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrConsultVoteCount', $this, $user);
    }

}
