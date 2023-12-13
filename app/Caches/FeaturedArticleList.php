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

    protected $lifetime = 3600;

    protected $limit = 5;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'featured_article_list';
    }

    public function getContent($id = null)
    {
        $articles = $this->findArticles($this->limit);

        if ($articles->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($articles as $article) {
            $result[] = [
                'id' => $article->id,
                'title' => $article->title,
                'cover' => $article->cover,
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
    protected function findArticles($limit = 5)
    {
        return ArticleModel::query()
            ->where('featured = 1')
            ->andWhere('published = :published:', ['published' => ArticleModel::PUBLISH_APPROVED])
            ->andWhere('deleted = 0')
            ->orderBy('RAND()')
            ->limit($limit)
            ->execute();
    }

}
