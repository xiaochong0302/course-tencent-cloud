<?php

namespace App\Listeners;

use App\Models\Review as ReviewModel;
use App\Services\Logic\Point\PointHistory as PointHistoryService;
use Phalcon\Events\Event as PhEvent;

class Review extends Listener
{

    public function afterCreate(PhEvent $event, $source, ReviewModel $review)
    {
        $this->handleReviewPoint($review);
    }

    protected function handleReviewPoint(ReviewModel $review)
    {
        $service = new PointHistoryService();

        $service->handleCourseReview($review);
    }

}