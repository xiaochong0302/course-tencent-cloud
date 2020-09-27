<?php

namespace App\Services\Logic\Consult;

use App\Models\Chapter as ChapterModel;
use App\Models\Consult as ConsultModel;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service;
use App\Validators\Consult as ConsultValidator;
use App\Validators\UserLimit as UserLimitValidator;

class ConsultCreate extends Service
{

    use CourseTrait;
    use ChapterTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $chapter = $this->checkChapter($post['chapter_id']);

        $course = $this->checkCourse($chapter->course_id);

        $validator = new UserLimitValidator();

        $validator->checkDailyConsultLimit($user);

        $validator = new ConsultValidator();

        $question = $validator->checkQuestion($post['question']);

        $validator->checkIfDuplicated($question, $chapter->id, $user->id);

        $priority = $this->getPriority($course, $user);

        $consult = new ConsultModel();

        $consult->question = $question;
        $consult->priority = $priority;
        $consult->course_id = $course->id;
        $consult->chapter_id = $chapter->id;
        $consult->owner_id = $user->id;
        $consult->published = 1;

        $consult->create();

        $this->incrCourseConsultCount($course);

        $this->incrChapterConsultCount($chapter);

        $this->incrUserDailyConsultCount($user);

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

    protected function incrCourseConsultCount(CourseModel $course)
    {
        $course->consult_count += 1;

        $course->update();
    }

    protected function incrChapterConsultCount(ChapterModel $chapter)
    {
        $chapter->consult_count += 1;

        $chapter->update();
    }

    protected function incrUserDailyConsultCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrConsultCount', $this, $user);
    }

}
