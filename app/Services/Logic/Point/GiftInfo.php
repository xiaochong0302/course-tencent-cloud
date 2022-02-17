<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Point;

use App\Models\PointGift;
use App\Repos\User as UserRepo;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\PointGiftTrait;
use App\Services\Logic\Service as LogicService;

class GiftInfo extends LogicService
{

    use CourseTrait;
    use PointGiftTrait;

    public function handle($id)
    {
        $gift = $this->checkPointGift($id);

        if ($gift->type == PointGift::TYPE_COURSE) {
            $gift = $this->getCourseGift($gift);
        }

        $gift->details = kg_parse_markdown($gift->details);

        $meInfo = $this->handleMeInfo($gift);

        return [
            'id' => $gift->id,
            'name' => $gift->name,
            'cover' => $gift->cover,
            'details' => $gift->details,
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

    protected function getCourseGift(PointGift $gift)
    {
        $courseId = $gift->attrs['id'] ?? 0;

        $course = $this->checkCourse($courseId);

        $gift->name = $course->title;
        $gift->cover = $course->cover;
        $gift->details = $course->details;
        $gift->attrs = [
            'id' => $course->id,
            'title' => $course->title,
            'price' => $course->market_price,
        ];

        return $gift;
    }

    protected function handleMeInfo(PointGift $gift)
    {
        $me = ['allow_redeem' => 0];

        $user = $this->getLoginUser(true);

        $userRepo = new UserRepo();

        $balance = $userRepo->findUserBalance($user->id);

        if ($gift->stock > 0 && $balance->point > $gift->point) {
            $me['allow_redeem'] = 1;
        }

        return $me;
    }

}
