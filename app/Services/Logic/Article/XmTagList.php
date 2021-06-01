<?php

namespace App\Services\Logic\Article;

use App\Repos\Article as ArticleRepo;
use App\Repos\Tag as TagRepo;
use App\Services\Logic\Service as LogicService;

class XmTagList extends LogicService
{

    public function handle($id)
    {
        $tagRepo = new TagRepo();

        $allTags = $tagRepo->findAll(['published' => 1]);

        if ($allTags->count() == 0) return [];

        $articleTagIds = [];

        if ($id > 0) {
            $article = $this->findArticle($id);
            if (!empty($article->tags)) {
                $articleTagIds = kg_array_column($article->tags, 'id');
            }
        }

        $list = [];

        foreach ($allTags as $tag) {
            $selected = in_array($tag->id, $articleTagIds);
            $list[] = [
                'name' => $tag->name,
                'value' => $tag->id,
                'selected' => $selected,
            ];
        }

        return $list;
    }

    protected function findArticle($id)
    {
        $articleRepo = new ArticleRepo();

        return $articleRepo->findById($id);
    }

}
