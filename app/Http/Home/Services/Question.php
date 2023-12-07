<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Models\Category as CategoryModel;
use App\Models\Question as QuestionModel;
use App\Services\Category as CategoryService;
use App\Services\Logic\Question\XmTagList as XmTagListService;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;

class Question extends LogicService
{

    use QuestionTrait;

    public function getQuestionModel()
    {
        $question = new QuestionModel();

        $question->afterFetch();

        return $question;
    }

    public function getCategoryOptions()
    {
        $categoryService = new CategoryService();

        return $categoryService->getCategoryOptions(CategoryModel::TYPE_QUESTION);
    }

    public function getXmTags($id)
    {
        $service = new XmTagListService();

        return $service->handle($id);
    }

    public function getQuestion($id)
    {
        return $this->checkQuestion($id);
    }

}
