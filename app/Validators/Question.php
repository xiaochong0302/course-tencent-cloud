<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Caches\MaxQuestionId as MaxQuestionIdCache;
use App\Caches\Question as QuestionCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Question as QuestionModel;
use App\Models\Reason as ReasonModel;
use App\Repos\Question as QuestionRepo;

class Question extends Validator
{

    /**
     * @param int $id
     * @return QuestionModel
     * @throws BadRequestException
     */
    public function checkQuestionCache($id)
    {
        $this->checkId($id);

        $questionCache = new QuestionCache();

        $question = $questionCache->get($id);

        if (!$question) {
            throw new BadRequestException('question.not_found');
        }

        return $question;
    }

    public function checkQuestion($id)
    {
        $this->checkId($id);

        $questionRepo = new QuestionRepo();

        $question = $questionRepo->findById($id);

        if (!$question) {
            throw new BadRequestException('question.not_found');
        }

        return $question;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxIdCache = new MaxQuestionIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException('question.not_found');
        }
    }

    public function checkCategory($id)
    {
        $validator = new Category();

        return $validator->checkCategory($id);
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 5) {
            throw new BadRequestException('question.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('question.title_too_long');
        }

        return $value;
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $length = kg_strlen($value);

        if ($length > 30000) {
            throw new BadRequestException('question.content_too_long');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        if (!array_key_exists($status, QuestionModel::publishTypes())) {
            throw new BadRequestException('question.invalid_publish_status');
        }

        return $status;
    }

    public function checkAnonymousStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('question.invalid_anonymous_status');
        }

        return $status;
    }

    public function checkCloseStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('question.invalid_close_status');
        }

        return $status;
    }

    public function checkRejectReason($reason)
    {
        if (!array_key_exists($reason, ReasonModel::questionRejectOptions())) {
            throw new BadRequestException('question.invalid_reject_reason');
        }
    }

    public function checkIfAllowEdit(QuestionModel $question)
    {
        $approved = $question->published == QuestionModel::PUBLISH_APPROVED;

        $answered = $question->answer_count > 0;

        if ($approved || $answered) {
            throw new BadRequestException('question.edit_not_allowed');
        }
    }

    public function checkIfAllowDelete(QuestionModel $question)
    {
        $approved = $question->published == QuestionModel::PUBLISH_APPROVED;

        $answered = $question->answer_count > 0;

        if ($approved && $answered) {
            throw new BadRequestException('question.delete_not_allowed');
        }
    }

}
