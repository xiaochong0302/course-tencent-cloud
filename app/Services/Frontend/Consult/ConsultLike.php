<?php

namespace App\Services\Frontend\Consult;

use App\Models\Consult as ConsultModel;
use App\Models\ConsultLike as ConsultLikeModel;
use App\Models\User as UserModel;
use App\Repos\ConsultLike as ConsultLikeRepo;
use App\Services\Frontend\ConsultTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;
use Phalcon\Di as Di;
use Phalcon\Events\Manager as EventsManager;

class ConsultLike extends FrontendService
{

    use ConsultTrait;

    public function handle($id)
    {
        $consult = $this->checkConsult($id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkConsultLikeLimit($user);

        $consultLikeRepo = new ConsultLikeRepo();

        $consultLike = $consultLikeRepo->findConsultLike($consult->id, $user->id);

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
        $this->getPhEventsManager()->fire('consultCounter:incrLikeCount', $this, $consult);
    }

    protected function decrLikeCount(ConsultModel $consult)
    {
        $this->getPhEventsManager()->fire('consultCounter:decrLikeCount', $this, $consult);
    }

    protected function incrUserDailyConsultLikeCount(UserModel $user)
    {
        $this->getPhEventsManager()->fire('userDailyCounter:incrConsultLikeCount', $this, $user);
    }

    /**
     * @return EventsManager
     */
    protected function getPhEventsManager()
    {
        return Di::getDefault()->get('eventsManager');
    }

}
