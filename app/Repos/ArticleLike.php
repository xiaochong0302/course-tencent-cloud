<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\ArticleLike as ArticleLikeModel;
use Phalcon\Mvc\Model;

class ArticleLike extends Repository
{

    /**
     * @param int $articleId
     * @param int $userId
     * @return ArticleLikeModel|Model|bool
     */
    public function findArticleLike($articleId, $userId)
    {
        return ArticleLikeModel::findFirst([
            'conditions' => 'article_id = :article_id: AND user_id = :user_id:',
            'bind' => ['article_id' => $articleId, 'user_id' => $userId],
        ]);
    }

}
