<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Consult;

use App\Models\Consult as ConsultModel;
use App\Models\User as UserModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\ConsultLike as ConsultLikeRepo;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\ConsultTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class ConsultInfo extends LogicService
{

    use ConsultTrait;
    use UserTrait;

    public function handle($id)
    {
        $consult = $this->checkConsult($id);

        $user = $this->getCurrentUser(true);

        return $this->handleConsult($consult, $user);
    }

    protected function handleConsult(ConsultModel $consult, UserModel $user)
    {
        $course = $this->handleCourseInfo($consult->course_id);
        $chapter = $this->handleChapterInfo($consult->chapter_id);
        $replier = $this->handleShallowUserInfo($consult->replier_id);
        $owner = $this->handleShallowUserInfo($consult->owner_id);
        $me = $this->handleMeInfo($consult, $user);

        return [
            'id' => $consult->id,
            'question' => $consult->question,
            'consult' => $consult->consult,
            'rating' => $consult->rating,
            'private' => $consult->private,
            'published' => $consult->published,
            'deleted' => $consult->deleted,
            'like_count' => $consult->like_count,
            'create_time' => $consult->create_time,
            'update_time' => $consult->update_time,
            'course' => $course,
            'chapter' => $chapter,
            'replier' => $replier,
            'owner' => $owner,
            'me' => $me,
        ];
    }

    protected function handleCourseInfo($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        if (!$course) return new \stdClass();

        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
        ];
    }

    protected function handleChapterInfo($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($chapterId);

        if (!$chapter) return new \stdClass();

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
        ];
    }

    protected function handleMeInfo(ConsultModel $consult, UserModel $user)
    {
        $me = [
            'liked' => 0,
            'owned' => 0,
        ];

        if ($user->id == $consult->owner_id) {
            $me['owned'] = 1;
        }

        if ($user->id > 0) {

            $likeRepo = new ConsultLikeRepo();

            $like = $likeRepo->findConsultLike($consult->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }
        }

        return $me;
    }

}
