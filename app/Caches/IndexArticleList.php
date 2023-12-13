<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Article as ArticleModel;
use App\Repos\Article as ArticleRepo;
use App\Services\Logic\Article\ArticleList as ArticleListService;

class IndexArticleList extends Cache
{

    protected $lifetime = 3600;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index_article_list';
    }

    public function getContent($id = null)
    {
        $articleRepo = new ArticleRepo();

        $where = [
            'published' => ArticleModel::PUBLISH_APPROVED,
            'private' => 0,
            'deleted' => 0,
        ];

        $pager = $articleRepo->paginate($where, 'latest', 1, 10);

        $service = new ArticleListService();

        $pager = $service->handleArticles($pager);

        return $pager->items ?: [];
    }

}
