<?php

namespace App\Services\Frontend\Consult;

use App\Models\Consult as ConsultModel;
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

            $this->incrOpposeCount($consult);

        } else {

            if ($consultVote->type == ConsultVoteModel::TYPE_AGREE) {

                $consultVote->type = ConsultVoteModel::TYPE_OPPOSE;

                $this->decrAgreeCount($consult);

                $this->incrOpposeCount($consult);

            } elseif ($consultVote->type == ConsultVoteModel::TYPE_OPPOSE) {

                $consultVote->type = ConsultVoteModel::TYPE_NONE;

                $this->decrOpposeCount($consult);

            } elseif ($consultVote->type == ConsultVoteModel::TYPE_NONE) {

                $consultVote->type = ConsultVoteModel::TYPE_OPPOSE;

                $this->incrOpposeCount($consult);
            }

            $consultVote->update();
        }

        $this->incrUserDailyConsultVoteCount($user);

        return $consult;
    }

    protected function incrAgreeCount(ConsultModel $consult)
    {
        $this->eventsManager->fire('consultCounter:incrAgreeCount', $this, $consult);
    }

    protected function decrAgreeCount(ConsultModel $consult)
    {
        $this->eventsManager->fire('consultCounter:decrAgreeCount', $this, $consult);
    }

    protected function incrOpposeCount(ConsultModel $consult)
    {
        $this->eventsManager->fire('consultCounter:incrOpposeCount', $this, $consult);
    }

    protected function decrOpposeCount(ConsultModel $consult)
    {
        $this->eventsManager->fire('consultCounter:decrOpposeCount', $this, $consult);
    }

    protected function incrUserDailyConsultVoteCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrConsultVoteCount', $this, $user);
    }

}
