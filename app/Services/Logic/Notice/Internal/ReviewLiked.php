<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\Internal;

use App\Models\Notification as NotificationModel;
use App\Models\Review as ReviewModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\Service as LogicService;

class ReviewLiked extends LogicService
{

    public function handle(ReviewModel $review, UserModel $sender)
    {
        $reviewContent = kg_substr($review->content, 0, 36);

        $course = $this->findCourse($review->course_id);

        $notification = new NotificationModel();

        $notification->sender_id = $sender->id;
        $notification->receiver_id = $review->owner_id;
        $notification->event_id = $review->id;
        $notification->event_type = NotificationModel::TYPE_REVIEW_LIKED;
        $notification->event_info = [
            'course' => ['id' => $course->id, 'title' => $course->title],
            'review' => ['id' => $review->id, 'content' => $reviewContent],
        ];

        $notification->create();
    }

    protected function findCourse($id)
    {
        $courseRepo = new CourseRepo();

        return $courseRepo->findById($id);
    }

}
