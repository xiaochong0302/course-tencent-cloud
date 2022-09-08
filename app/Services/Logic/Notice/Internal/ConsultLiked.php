<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\Internal;

use App\Models\Consult as ConsultModel;
use App\Models\Notification as NotificationModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\Service as LogicService;

class ConsultLiked extends LogicService
{

    public function handle(ConsultModel $consult, UserModel $sender)
    {
        $consultQuestion = kg_substr($consult->question, 0, 36);

        $course = $this->findCourse($consult->course_id);

        $notification = new NotificationModel();

        $notification->sender_id = $sender->id;
        $notification->receiver_id = $consult->owner_id;
        $notification->event_id = $consult->id;
        $notification->event_type = NotificationModel::TYPE_CONSULT_LIKED;
        $notification->event_info = [
            'course' => ['id' => $course->id, 'title' => $course->title],
            'consult' => ['id' => $consult->id, 'question' => $consultQuestion],
        ];

        $notification->create();
    }

    protected function findCourse($id)
    {
        $courseRepo = new CourseRepo();

        return $courseRepo->findById($id);
    }

}
