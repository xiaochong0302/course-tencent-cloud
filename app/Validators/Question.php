<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Question as QuestionModel;
use App\Repos\Question as QuestionRepo;
use App\Services\EditorStorage as EditorStorageService;

class Question extends Validator
{

    public function checkQuestion($id)
    {
        $questionRepo = new QuestionRepo();

        $question = $questionRepo->findById($id);

        if (!$question) {
            throw new BadRequestException('question.not_found');
        }

        return $question;
    }

    public function checkCategoryId($id)
    {
        $result = 0;

        if ($id > 0) {
            $validator = new Category();
            $category = $validator->checkCategory($id);
            $result = $category->id;
        }

        return $result;
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

    public function checkSummary($summary)
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('question.summary_too_long');
        }

        return $value;
    }

    public function checkKeywords($keywords)
    {
        $keywords = $this->filter->sanitize($keywords, ['trim', 'string']);

        $length = kg_strlen($keywords);

        if ($length > 100) {
            throw new BadRequestException('question.keyword_too_long');
        }

        return kg_parse_keywords($keywords);
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $storage = new EditorStorageService();

        $value = $storage->handle($value);

        $length = kg_editor_content_length($value);

        if ($length > 30000) {
            throw new BadRequestException('question.content_too_long');
        }

        return kg_clean_html($value);
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

    public function checkFeatureStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('question.invalid_feature_status');
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
