<?php

namespace App\Http\Home\Services;

use App\Models\Article as ArticleModel;
use App\Models\Category as CategoryModel;
use App\Models\Reason as ReasonModel;
use App\Repos\Category as CategoryRepo;
use App\Services\Logic\Article\XmTagList as XmTagListService;
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
        $service = new XmTagListService();

        return $service->handle($id);
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

    public function getReportReasons()
    {
        return ReasonModel::reportOptions();
    }

    public function getArticle($id)
    {
        return $this->checkArticle($id);
    }

}
