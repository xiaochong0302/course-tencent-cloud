<?php

namespace App\Repos;

use App\Models\ArticleFavorite as ArticleFavoriteModel;
use Phalcon\Mvc\Model;

class ArticleFavorite extends Repository
{

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
