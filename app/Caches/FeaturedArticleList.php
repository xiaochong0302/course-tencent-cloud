<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Article as ArticleModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class FeaturedArticleList extends Cache
{

    protected $lifetime = 86400;

    public function getLifetime()
    {
        $tomorrow = strtotime('tomorrow');

        return $tomorrow - time();
    }

    public function getKey($id = null)
    {
        return 'featured_article_list';
    }

    public function getContent($id = null)
    {
        $limit = 8;

        $articles = $this->findArticles($limit);

        if ($articles->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($articles as $article) {

            $userCount = $article->user_count;

            if ($article->fake_user_count > $article->user_count) {
                $userCount = $article->fake_user_count;
            }

            $result[] = [
                'id' => $article->id,
                'title' => $article->title,
                'cover' => $article->cover,
                'market_price' => (float)$article->market_price,
                'vip_price' => (float)$article->vip_price,
                'user_count' => $userCount,
                'favorite_count' => $article->favorite_count,
                'comment_count' => $article->comment_count,
                'view_count' => $article->view_count,
                'like_count' => $article->like_count,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|ArticleModel[]
     */
    protected function findArticles($limit = 8)
    {
        return ArticleModel::query()
            ->where('featured = 1')
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('RAND()')
            ->limit($limit)
            ->execute();
    }

}
