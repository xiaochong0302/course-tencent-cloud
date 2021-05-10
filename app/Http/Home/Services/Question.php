<?php

namespace App\Http\Home\Services;

use App\Models\Category as CategoryModel;
use App\Models\Question as QuestionModel;
use App\Repos\Category as CategoryRepo;
use App\Repos\Tag as TagRepo;
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

    public function getCategories()
    {
        $categoryRepo = new CategoryRepo();

        return $categoryRepo->findAll([
            'type' => CategoryModel::TYPE_ARTICLE,
            'level' => 1,
            'published' => 1,
        ]);
    }

    public function getXmTags($id)
    {
        $tagRepo = new TagRepo();

        $allTags = $tagRepo->findAll(['published' => 1], 'priority');

        if ($allTags->count() == 0) return [];

        $questionTagIds = [];

        if ($id > 0) {
            $question = $this->checkQuestion($id);
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

    public function getQuestion($id)
    {
        return $this->checkQuestion($id);
    }

}
