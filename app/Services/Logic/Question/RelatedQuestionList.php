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
        $limit = $this->request->getQuery('limit', 'int', 5);

        $question = $this->checkQuestion($id);

        if (empty($question->tags)) return [];

        $tagIds = kg_array_column($question->tags, 'id');

        $tagId = kg_array_rand($tagIds);

        $cache = new TaggedQuestionListCache();

        $questions = $cache->get($tagId);

        if (empty($questions)) return [];

        foreach ($questions as $key => $question) {
            if ($question['id'] == $id) {
                unset($questions[$key]);
            }
        }

        if ($limit < count($questions)) {
            $questions = array_slice($questions, $limit);
        }

        return $questions;
    }

}
