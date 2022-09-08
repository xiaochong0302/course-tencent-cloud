<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Consult;

use App\Models\Chapter as ChapterModel;
use App\Models\Consult as ConsultModel;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Notice\External\DingTalk\ConsultCreate as ConsultCreateNotice;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;
use App\Validators\Consult as ConsultValidator;
use App\Validators\UserLimit as UserLimitValidator;

class ConsultCreate extends LogicService
{

    use ChapterTrait;
    use ClientTrait;
    use CourseTrait;

    public function handle()
    {
        $chapterId = $this->request->getPost('chapter_id', 'int', 0);
        $courseId = $this->request->getPost('course_id', 'int', 0);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyConsultLimit($user);

        $validator = new UserLimitValidator();

        $validator->checkDailyConsultLimit($user);

        if ($chapterId > 0) {

            $chapter = $this->checkChapter($chapterId);

            return $this->handleChapterConsult($chapter, $user);

        } elseif ($courseId > 0) {

            $course = $this->checkCourse($courseId);

            return $this->handleCourseConsult($course, $user);
        }
    }

    protected function handleCourseConsult(CourseModel $course, UserModel $user)
    {
        $post = $this->request->getPost();

        $validator = new ConsultValidator();

        $question = $validator->checkQuestion($post['question']);

        $private = 0;

        if (isset($post['private'])) {
            $private = $validator->checkPrivateStatus($post['private']);
        }

        $validator->checkIfDuplicated($course->id, $user->id, $question);

        $priority = $this->getPriority($course, $user);

        $consult = new ConsultModel();

        $consult->question = $question;
        $consult->private = $private;
        $consult->priority = $priority;
        $consult->course_id = $course->id;
        $consult->owner_id = $user->id;
        $consult->client_type = $this->getClientType();
        $consult->client_ip = $this->getClientIp();
        $consult->published = 1;

        $consult->create();

        $this->recountCourseConsults($course);
        $this->incrUserDailyConsultCount($user);
        $this->handleConsultCreateNotice($consult);

        $this->eventsManager->fire('Consult:afterCreate', $this, $consult);

        return $consult;
    }

    protected function handleChapterConsult(ChapterModel $chapter, UserModel $user)
    {
        $course = $this->checkCourse($chapter->course_id);

        $post = $this->request->getPost();

        $validator = new ConsultValidator();

        $question = $validator->checkQuestion($post['question']);

        $private = 0;

        if (isset($post['private'])) {
            $private = $validator->checkPrivateStatus($post['private']);
        }

        $validator->checkIfDuplicated($course->id, $user->id, $question);

        $priority = $this->getPriority($course, $user);

        $consult = new ConsultModel();

        $consult->question = $question;
        $consult->private = $private;
        $consult->priority = $priority;
        $consult->course_id = $course->id;
        $consult->chapter_id = $chapter->id;
        $consult->owner_id = $user->id;
        $consult->client_type = $this->getClientType();
        $consult->client_ip = $this->getClientIp();
        $consult->published = 1;

        $consult->create();

        $this->recountCourseConsults($course);
        $this->recountChapterConsults($chapter);
        $this->incrUserDailyConsultCount($user);
        $this->handleConsultCreateNotice($consult);

        $this->eventsManager->fire('Consult:afterCreate', $this, $consult);

        return $consult;
    }

    protected function getPriority(CourseModel $course, UserModel $user)
    {
        $charge = $course->market_price > 0;

        $vip = $user->vip == 1;

        if ($vip && $charge) {
            $priority = ConsultModel::PRIORITY_HIGH;
        } elseif ($charge) {
            $priority = ConsultModel::PRIORITY_MIDDLE;
        } else {
            $priority = ConsultModel::PRIORITY_LOW;
        }

        return $priority;
    }

    protected function recountCourseConsults(CourseModel $course)
    {
        $courseRepo = new CourseRepo();

        $consultCount = $courseRepo->countConsults($course->id);

        $course->consult_count = $consultCount;

        $course->update();
    }

    protected function recountChapterConsults(ChapterModel $chapter)
    {
        $chapterRepo = new ChapterRepo();

        $consultCount = $chapterRepo->countConsults($chapter->id);

        $chapter->consult_count = $consultCount;

        $chapter->update();
    }

    protected function incrUserDailyConsultCount(UserModel $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrConsultCount', $this, $user);
    }

    protected function handleConsultCreateNotice(ConsultModel $consult)
    {
        $notice = new ConsultCreateNotice();

        $notice->createTask($consult);
    }

}
