<?php

namespace App\Validators;

use App\Caches\MaxAnswerId as MaxAnswerIdCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Answer as AnswerModel;
use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Repos\Answer as AnswerRepo;
use App\Repos\Question as QuestionRepo;

class Answer extends Validator
{

    public function checkAnswer($id)
    {
        $this->checkId($id);

        $answerRepo = new AnswerRepo();

        $answer = $answerRepo->findById($id);

        if (!$answer) {
            throw new BadRequestException('answer.not_found');
        }

        return $answer;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxIdCache = new MaxAnswerIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException('answer.not_found');
        }
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 10) {
            throw new BadRequestException('answer.content_too_short');
        }

        if ($length > 30000) {
            throw new BadRequestException('answer.content_too_long');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        if (!array_key_exists($status, AnswerModel::publishTypes())) {
            throw new BadRequestException('answer.invalid_publish_status');
        }

        return $status;
    }

    public function checkIfAllowAnswer(QuestionModel $question, UserModel $user)
    {
        $allowed = true;

        $questionRepo = new QuestionRepo();

        $answers = $questionRepo->findUserAnswers($question->id, $user->id);

        if ($answers->count() > 0) {
            $allowed = false;
        }

        if ($question->closed == 1 || $question->solved == 1) {
            $allowed = false;
        }

        if (!$allowed) {
            throw new BadRequestException('answer.post_not_allowed');
        }
    }

    public function checkIfAllowEdit(AnswerModel $answer, UserModel $user)
    {
        $this->checkOwner($user->id, $answer->owner_id);

        if (time() - $answer->create_time > 3600) {
            throw new BadRequestException('answer.edit_not_allowed');
        }
    }

}
