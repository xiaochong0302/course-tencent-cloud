<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\ArticleFavorite as ArticleFavoriteModel;
use Phalcon\Mvc\Model;

class ArticleFavorite extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ArticleFavoriteModel::class);

        $builder->where('1 = 1');

        if (!empty($where['article_id'])) {
            $builder->andWhere('article_id = :article_id:', ['article_id' => $where['article_id']]);
        }

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        switch ($sort) {
            case 'oldest':
                $orderBy = 'id ASC';
                break;
            default:
                $orderBy = 'id DESC';
                break;
        }

        $builder->orderBy($orderBy);

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

    /**
     * @param int $articleId
     * @param int $userId
     * @return ArticleFavoriteModel|Model|bool
     */
    public function findArticleFavorite($articleId, $userId)
    {
        return ArticleFavoriteModel::findFirst([
            'conditions' => 'article_id = :article_id: AND user_id = :user_id:',
            'bind' => ['article_id' => $articleId, 'user_id' => $userId],
        ]);
    }

}
