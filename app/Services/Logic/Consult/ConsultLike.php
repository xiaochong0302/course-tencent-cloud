<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Consult;

use App\Models\Consult as ConsultModel;
use App\Models\ConsultLike as ConsultLikeModel;
use App\Models\User as UserModel;
use App\Repos\ConsultLike as ConsultLikeRepo;
use App\Services\Logic\ConsultTrait;
use App\Services\Logic\Notice\Internal\ConsultLiked as ConsultLikedNotice;
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

        $isFirstTime = true;

        if (!$consultLike) {

            $consultLike = new ConsultLikeModel();

            $consultLike->consult_id = $consult->id;
            $consultLike->user_id = $user->id;

            $consultLike->create();

        } else {

            $isFirstTime = false;

            $consultLike->deleted = $consultLike->deleted == 1 ? 0 : 1;

            $consultLike->update();
        }

        $this->incrUserDailyConsultLikeCount($user);

        if ($consultLike->deleted == 0) {

            $action = 'do';

            $this->incrConsultLikeCount($consult);

            $this->eventsManager->fire('Consult:afterLike', $this, $consult);

        } else {

            $action = 'undo';

            $this->decrConsultLikeCount($consult);

            $this->eventsManager->fire('Consult:afterUndoLike', $this, $consult);
        }

        $isOwner = $user->id == $consult->owner_id;

        /**
         * 仅首次点赞发送通知
         */
        if ($isFirstTime && !$isOwner) {
            $this->handleConsultLikedNotice($consult, $user);
        }

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

    protected function handleConsultLikedNotice(ConsultModel $consult, UserModel $sender)
    {
        $notice = new ConsultLikedNotice();

        $notice->handle($consult, $sender);
    }

}
