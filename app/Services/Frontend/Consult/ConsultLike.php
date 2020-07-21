<?php

namespace App\Services\Frontend\Consult;

use App\Models\Consult as ConsultModel;
use App\Models\ConsultLike as ConsultLikeModel;
use App\Models\User as UserModel;
use App\Services\Frontend\ConsultTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Consult as ConsultValidator;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class ConsultLike extends FrontendService
{

    use ConsultTrait;

    public function handle($id)
    {
        $consult = $this->checkConsult($id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkConsultLikeLimit($user);

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

            if ($consultLike->deleted == 0) {

                $consultLike->update(['deleted' => 1]);

                $this->decrLikeCount($consult);

            } else {

                $consultLike->update(['deleted' => 0]);

                $this->incrLikeCount($consult);
            }
        }

        $this->incrUserDailyConsultLikeCount($user);

        return $consult;
    }

    protected function incrLikeCount(ConsultModel $consult)
    {
        $consult->like_count += 1;

        $consult->update();
    }

    protected function decrLikeCount(ConsultModel $consult)
    {
        $consult->like_count -= 1;

        $consult->update();
    }

    protected function incrUserDailyConsultLikeCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrConsultLikeCount', $this, $user);
    }

}
