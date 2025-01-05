<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Consult;

use App\Models\Consult as ConsultModel;
use App\Models\User as UserModel;
use App\Repos\ConsultLike as ConsultLikeRepo;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\ConsultTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\ShallowUserInfo;

class ConsultInfo extends LogicService
{

    use ConsultTrait;

    public function handle($id)
    {
        $consult = $this->checkConsult($id);

        $user = $this->getCurrentUser();

        return $this->handleConsult($consult, $user);
    }

    protected function handleConsult(ConsultModel $consult, UserModel $user)
    {
        $course = $this->handleCourseInfo($consult->course_id);
        $replier = $this->handleReplierInfo($consult->replier_id);
        $owner = $this->handleOwnerInfo($consult->owner_id);
        $me = $this->handleMeInfo($consult, $user);

        return [
            'id' => $consult->id,
            'question' => $consult->question,
            'answer' => $consult->answer,
            'rating' => $consult->rating,
            'private' => $consult->private,
            'published' => $consult->published,
            'deleted' => $consult->deleted,
            'like_count' => $consult->like_count,
            'create_time' => $consult->create_time,
            'update_time' => $consult->update_time,
            'course' => $course,
            'replier' => $replier,
            'owner' => $owner,
            'me' => $me,
        ];
    }

    protected function handleCourseInfo($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
        ];
    }

    protected function handleOwnerInfo($userId)
    {
        $service = new ShallowUserInfo();

        return $service->handle($userId);
    }

    protected function handleReplierInfo($userId)
    {
        if ($userId == 0) return null;

        $service = new ShallowUserInfo();

        return $service->handle($userId);
    }

    protected function handleMeInfo(ConsultModel $consult, UserModel $user)
    {
        $me = [
            'logged' => 0,
            'liked' => 0,
            'owned' => 0,
        ];

        if ($user->id > 0) {

            if ($user->id == $consult->owner_id) {
                $me['owned'] = 1;
            }

            $me['logged'] = 1;

            $likeRepo = new ConsultLikeRepo();

            $like = $likeRepo->findConsultLike($consult->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }
        }

        return $me;
    }

}
