<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\Forbidden as ForbiddenException;
use App\Models\Consult as ConsultModel;
use App\Models\User as UserModel;
use App\Repos\Consult as ConsultRepo;
use App\Repos\ConsultLike as ConsultLikeRepo;
use App\Repos\Course as CourseRepo;

class Consult extends Validator
{

    public function checkConsult($id)
    {
        $consultRepo = new ConsultRepo();

        $consult = $consultRepo->findById($id);

        if (!$consult) {
            throw new BadRequestException('consult.not_found');
        }

        return $consult;
    }

    public function checkCourse($id)
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkChapter($id)
    {
        $validator = new Chapter();

        return $validator->checkChapter($id);
    }

    public function checkQuestion($question)
    {
        $value = $this->filter->sanitize($question, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 15) {
            throw new BadRequestException('consult.question_too_short');
        }

        if ($length > 1000) {
            throw new BadRequestException('consult.question.too_long');
        }

        return $value;
    }

    public function checkAnswer($answer)
    {
        $value = $this->filter->sanitize($answer, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 15) {
            throw new BadRequestException('consult.answer_too_short');
        }

        if ($length > 1000) {
            throw new BadRequestException('consult.answer_too_long');
        }

        return $value;
    }

    public function checkPrivateStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('consult.invalid_private_status');
        }

        return $status;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('consult.invalid_publish_status');
        }

        return $status;
    }

    public function checkTeacher(ConsultModel $consult, UserModel $user)
    {
        $repo = new CourseRepo();

        $teachers = $repo->findTeachers($consult->course_id);

        $isTeacher = false;

        if ($teachers->count() > 0) {
            $teacherIds = kg_array_column($teachers->toArray(), 'id');
            $isTeacher = in_array($user->id, $teacherIds);
        }

        if (!$isTeacher) {
            throw new ForbiddenException('sys.forbidden');
        }
    }

    public function checkConsultEdit(ConsultModel $consult)
    {
        /**
         * (1)已回复不允许修改提问
         * (2)发表三天以后不能修改提问
         */
        $case1 = $consult->reply_time > 0;
        $case2 = time() - $consult->create_time > 3 * 86400;

        if ($case1 || $case2) {
            throw new BadRequestException('consult.edit_not_allowed');
        }
    }

    public function checkIfDuplicated($question, $chapterId, $userId)
    {
        $repo = new ConsultRepo();

        $consult = $repo->findUserLastChapterConsult($chapterId, $userId);

        if (!$consult) return;

        $subInQuestion = kg_substr($question, 0, 20);
        $subDbQuestion = kg_substr($consult->question, 0, 20);

        similar_text($subInQuestion, $subDbQuestion, $percent);

        if ($percent > 80) {
            throw new BadRequestException('consult.question_duplicated');
        }

        similar_text($question, $consult->question, $percent);

        if ($percent > 80) {
            throw new BadRequestException('consult.question_duplicated');
        }
    }

    public function checkIfLiked($chapterId, $userId)
    {
        $repo = new ConsultLikeRepo();

        $like = $repo->findConsultLike($chapterId, $userId);

        if ($like) {
            if ($like->deleted == 0 && time() - $like->create_time > 5 * 60) {
                throw new BadRequestException('consult.has_liked');
            }
        }

        return $like;
    }

}
