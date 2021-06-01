<?php

namespace App\Services\Logic\Question;

use App\Repos\Question as QuestionRepo;
use App\Repos\Tag as TagRepo;
use App\Services\Logic\Service as LogicService;

class XmTagList extends LogicService
{

    public function handle($id)
    {
        $tagRepo = new TagRepo();

        $allTags = $tagRepo->findAll(['published' => 1]);

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
            $selected = in_array($tag->id, $questionTagIds);
            $list[] = [
                'name' => $tag->name,
                'value' => $tag->id,
                'selected' => $selected,
            ];
        }

        return $list;
    }

    protected function findQuestion($id)
    {
        $questionRepo = new QuestionRepo();

        return $questionRepo->findById($id);
    }

}
