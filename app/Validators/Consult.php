<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Consult as ConsultRepo;
use App\Repos\ConsultLike as ConsultLikeRepo;

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
