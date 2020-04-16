<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Consult as ConsultRepo;
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

    public function checkCourse($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        if (!$course) {
            throw new BadRequestException('consult.invalid_course_id');
        }

        return $course;
    }

    public function checkQuestion($question)
    {
        $value = $this->filter->sanitize($question, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 5) {
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

        if ($length < 5) {
            throw new BadRequestException('consult.answer_too_short');
        }

        if ($length > 1000) {
            throw new BadRequestException('consult.answer_too_long');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('consult.invalid_publish_status');
        }

        return $status;
    }

}
