<?php

namespace App\Services\Logic\Consult;

use App\Models\Consult as ConsultModel;
use App\Models\ConsultLike as ConsultLikeModel;
use App\Models\User as UserModel;
use App\Services\Logic\ConsultTrait;
use App\Services\Logic\Service;
use App\Validators\Consult as ConsultValidator;
use App\Validators\UserLimit as UserLimitValidator;

class ConsultLike extends Service
{

    use ConsultTrait;

    public function handle($id)
    {
        $consult = $this->checkConsult($id);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyConsultLikeLimit($user);

        $validator = new ConsultValidator();

        $consultLike = $validator->checkIfLiked($consult->id, $user->id);

        if (!$consultLike) {

            $consultLike = new ConsultLikeModel();

            $consultLike->create([
                'consult_id' => $consult->id,
                'user_id' => $user->id,
            ]);

            $this->incrLikeCount($consult);

        } else {

            $consultLike->delete();

            $this->decrLikeCount($consult);
        }

        $this->incrUserDailyConsultLikeCount($user);

        return $consultLike;
    }

    protected function incrLikeCount(ConsultModel $consult)
    {
        $consult->like_count += 1;

        $consult->update();
    }

    protected function decrLikeCount(ConsultModel $consult)
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
