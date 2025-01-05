<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Answer as AnswerModel;
use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Repos\Answer as AnswerRepo;
use App\Repos\Question as QuestionRepo;
use App\Services\EditorStorage as EditorStorageService;

class Answer extends Validator
{

    public function checkAnswer($id)
    {
        $answerRepo = new AnswerRepo();

        $answer = $answerRepo->findById($id);

        if (!$answer) {
            throw new BadRequestException('answer.not_found');
        }

        return $answer;
    }

    public function checkQuestion($id)
    {
        $validator = new Question();

        return $validator->checkQuestion($id);
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $storage = new EditorStorageService();

        $value = $storage->handle($value);

        $length = kg_editor_content_length($value);

        if ($length < 10) {
            throw new BadRequestException('answer.content_too_short');
        }

        if ($length > 30000) {
            throw new BadRequestException('answer.content_too_long');
        }

        return kg_clean_html($value);
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

    public function checkIfAllowEdit(AnswerModel $answer)
    {
        if ($answer->accepted == 1) {
            throw new BadRequestException('answer.edit_not_allowed');
        }

        $case1 = $answer->published == AnswerModel::PUBLISH_APPROVED;
        $case2 = time() - $answer->create_time > 3600;

        if ($case1 && $case2) {
            throw new BadRequestException('answer.edit_not_allowed');
        }
    }

    public function checkIfAllowDelete(AnswerModel $answer)
    {
        if ($answer->accepted == 1) {
            throw new BadRequestException('answer.delete_not_allowed');
        }
    }

}
