<?php

namespace App\Services\Logic\Consult;

use App\Models\Consult as ConsultModel;
use App\Models\ConsultLike as ConsultLikeModel;
use App\Models\User as UserModel;
use App\Repos\ConsultLike as ConsultLikeRepo;
use App\Services\Logic\ConsultTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\UserLimit as UserLimitValidator;

class ConsultLike extends LogicService
{

    use ConsultTrait;

    public function handle($id)
    {
        $consult = $this->checkConsult($id);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyConsultLikeLimit($user);

        $likeRepo = new ConsultLikeRepo();

        $consultLike = $likeRepo->findConsultLike($consult->id, $user->id);

        if (!$consultLike) {

            $action = 'do';

            $consultLike = new ConsultLikeModel();

            $consultLike->consult_id = $consult->id;
            $consultLike->user_id = $user->id;

            $consultLike->create();

            $this->incrConsultLikeCount($consult);

        } else {

            $action = 'undo';

            $consultLike->delete();

            $this->decrConsultLikeCount($consult);
        }

        $this->incrUserDailyConsultLikeCount($user);

        return [
            'action' => $action,
            'count' => $consult->like_count,
        ];
    }

    protected function incrConsultLikeCount(ConsultModel $consult)
    {
        $consult->like_count += 1;

        $consult->update();
    }

    protected function decrConsultLikeCount(ConsultModel $consult)
    {
        if ($consult->like_count > 0) {
            $consult->like_count -= 1;
            $consult->update();
        }
    }

    protected function incrUserDailyConsultLikeCount(UserModel $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrConsultLikeCount', $this, $user);
    }

}
