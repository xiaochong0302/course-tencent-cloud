<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Question;

use App\Models\Tag as TagModel;
use App\Repos\Question as QuestionRepo;
use App\Repos\Tag as TagRepo;
use App\Services\Logic\Service as LogicService;

class XmTagList extends LogicService
{

    public function handle($id)
    {
        $tagRepo = new TagRepo();

        $allTags = $tagRepo->findAll([
            'published' => 1,
            'deleted' => 0,
        ]);

        if ($allTags->count() == 0) return [];

        $questionTagIds = [];

        if ($id > 0) {
            $question = $this->findQuestion($id);
            if (!empty($question->tags)) {
                $questionTagIds = kg_array_column($question->tags, 'id');
            }
        }

        $list = [];

        foreach ($allTags as $tag) {
            $case1 = is_string($tag->scopes) && $tag->scopes == 'all';
            $case2 = is_array($tag->scopes) && in_array(TagModel::SCOPE_QUESTION, $tag->scopes);
            if ($case1 || $case2) {
                $selected = in_array($tag->id, $questionTagIds);
                $list[] = [
                    'name' => $tag->name,
                    'value' => $tag->id,
                    'selected' => $selected,
                ];
            }
        }

        return $list;
    }

    protected function findQuestion($id)
    {
        $questionRepo = new QuestionRepo();

        return $questionRepo->findById($id);
    }

}
