<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Point;

use App\Models\PointGift;
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

        return [
            'id' => $gift->id,
            'name' => $gift->name,
            'cover' => $gift->cover,
            'details' => $gift->details,
            'attrs' => $gift->attrs,
            'type' => $gift->type,
            'stock' => $gift->stock,
            'point' => $gift->point,
            'redeem_limit' => $gift->redeem_limit,
            'redeem_count' => $gift->redeem_count,
        ];
    }

    protected function getCourseGift(PointGift $gift)
    {
        $courseId = $gift->attrs['id'] ?? 0;

        $course = $this->checkCourse($courseId);

        $gift->name = $course->title;
        $gift->cover = $course->cover;
        $gift->details = $course->details;

        return $gift;
    }

}
