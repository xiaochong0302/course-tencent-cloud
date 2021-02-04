<?php

namespace App\Services\Logic\Point;

use App\Models\PointGift;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\PointGiftTrait;
use App\Services\Logic\Service;

class GiftInfo extends Service
{

    use CourseTrait;
    use PointGiftTrait;

    public function handle($id)
    {
        $gift = $this->checkGift($id);

        if ($gift->type == PointGift::TYPE_COURSE) {
            $gift = $this->getCourseGift($gift);
        }

        $gift->details = kg_parse_markdown($gift->details);

        return [
            'id' => $gift->id,
            'name' => $gift->name,
            'cover' => $gift->cover,
            'details' => $gift->details,
            'type' => $gift->type,
            'point' => $gift->point,
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
