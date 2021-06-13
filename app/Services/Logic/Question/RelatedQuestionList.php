<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Question;

use App\Caches\TaggedQuestionList as TaggedQuestionListCache;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;

class RelatedQuestionList extends LogicService
{

    use QuestionTrait;

    public function handle($id)
    {
        $question = $this->checkQuestion($id);

        if (empty($question->tags)) return [];

        $tagIds = kg_array_column($question->tags, 'id');

        $randKey = array_rand($tagIds);

        $tagId = $tagIds[$randKey];

        $cache = new TaggedQuestionListCache();

        $result = $cache->get($tagId);

        return $result ?: [];
    }

}
