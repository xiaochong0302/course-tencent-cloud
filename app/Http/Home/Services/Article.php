<?php

namespace App\Http\Home\Services;

use App\Models\Article as ArticleModel;
use App\Models\Category as CategoryModel;
use App\Repos\Category as CategoryRepo;
use App\Repos\Tag as TagRepo;
use App\Services\Logic\ArticleTrait;

class Article extends Service
{

    use ArticleTrait;

    public function getArticleModel()
    {
        $article = new ArticleModel();

        $article->afterFetch();

        return $article;
    }

    public function getXmTags($id)
    {
        $tagRepo = new TagRepo();

        $allTags = $tagRepo->findAll(['published' => 1], 'priority');

        if ($allTags->count() == 0) return [];

        $articleTagIds = [];

        if ($id > 0) {
            $article = $this->checkArticle($id);
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

    public function getCategories()
    {
        $categoryRepo = new CategoryRepo();

        return $categoryRepo->findAll([
            'type' => CategoryModel::TYPE_ARTICLE,
            'level' => 1,
            'published' => 1,
        ]);
    }

    public function getSourceTypes()
    {
        return ArticleModel::sourceTypes();
    }

    public function getArticle($id)
    {
        return $this->checkArticle($id);
    }

}
