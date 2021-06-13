<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\ArticleTag as ArticleTagModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ArticleTag extends Repository
{

    /**
     * @param int $articleId
     * @param int $tagId
     * @return ArticleTagModel|Model|bool
     */
    public function findArticleTag($articleId, $tagId)
    {
        return ArticleTagModel::findFirst([
            'conditions' => 'article_id = :article_id: AND tag_id = :tag_id:',
            'bind' => ['article_id' => $articleId, 'tag_id' => $tagId],
        ]);
    }

    /**
     * @param array $tagIds
     * @return ResultsetInterface|Resultset|ArticleTagModel[]
     */
    public function findByTagIds($tagIds)
    {
        return ArticleTagModel::query()
            ->inWhere('tag_id', $tagIds)
            ->execute();
    }

    /**
     * @param array $articleIds
     * @return ResultsetInterface|Resultset|ArticleTagModel[]
     */
    public function findByArticleIds($articleIds)
    {
        return ArticleTagModel::query()
            ->inWhere('article_id', $articleIds)
            ->execute();
    }

}
