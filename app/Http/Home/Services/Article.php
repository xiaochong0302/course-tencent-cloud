<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Models\Article as ArticleModel;
use App\Models\Category as CategoryModel;
use App\Services\Category as CategoryService;
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

    public function getCategoryOptions()
    {
        $categoryService = new CategoryService();

        return $categoryService->getCategoryOptions(CategoryModel::TYPE_ARTICLE);
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
