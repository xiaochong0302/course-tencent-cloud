<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Point;

use App\Models\PointGift as PointGiftModel;
use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Services\Logic\ContentTrait;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\PointGiftTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\VipTrait;

class GiftInfo extends LogicService
{

    use CourseTrait;
    use ContentTrait;
    use PointGiftTrait;
    use VipTrait;

    public function handle($id)
    {
        $gift = $this->checkPointGift($id);

        $user = $this->getCurrentUser();

        if ($gift->type == PointGiftModel::TYPE_COURSE) {
            $gift = $this->handleCoursePointGift($gift);
        } elseif ($gift->type == PointGiftModel::TYPE_VIP) {
            $gift = $this->handleVipPointGift($gift);
        }

        $details = $this->handleContent($gift->details);

        $meInfo = $this->handleMeInfo($gift, $user);

        return [
            'id' => $gift->id,
            'name' => $gift->name,
            'cover' => $gift->cover,
            'details' => $details,
            'attrs' => $gift->attrs,
            'type' => $gift->type,
            'stock' => $gift->stock,
            'point' => $gift->point,
            'published' => $gift->published,
            'deleted' => $gift->deleted,
            'redeem_limit' => $gift->redeem_limit,
            'redeem_count' => $gift->redeem_count,
            'create_time' => $gift->create_time,
            'update_time' => $gift->update_time,
            'me' => $meInfo,
        ];
    }

    protected function handleCoursePointGift(PointGiftModel $gift)
    {
        $id = $gift->attrs['id'] ?? 0;

        if ($id == 0) return $gift;

        $course = $this->checkCourse($id);

        $gift->name = $course->title;
        $gift->cover = $course->cover;
        $gift->details = $course->details;

        return $gift;
    }

    protected function handleVipPointGift(PointGiftModel $gift)
    {
        $id = $gift->attrs['id'] ?? 0;

        if ($id == 0) return $gift;

        $vip = $this->checkVip($id);

        $gift->name = sprintf('会员服务（%d个月）', $vip->expiry);
        $gift->cover = $vip->cover;

        return $gift;
    }

    protected function handleMeInfo(PointGiftModel $gift, UserModel $user)
    {
        $me = [
            'allow_redeem' => 0,
            'logged' => 0,
        ];

        if ($user->id > 0) {

            $me['logged'] = 1;

            $userRepo = new UserRepo();

            $balance = $userRepo->findUserBalance($user->id);

            if ($gift->stock > 0 && $balance->point > $gift->point) {
                $me['allow_redeem'] = 1;
            }
        }

        return $me;
    }

}
